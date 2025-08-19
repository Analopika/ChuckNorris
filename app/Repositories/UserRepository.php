<?php

declare(strict_types= 1);

namespace App\Repositories;
 ini_set('display_errors',1);
 error_reporting(E_ALL);
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Config\DB as DB;
use Firebase\JWT\JWT;
use FireBAse\JWT\Key;

class UserRepository
{
    private string $jwtSecret;
    private int $accessTokenExp = 3600;
    private int $refreshTokenExp = 604800;

    public function __construct()
    {
        $this->jwtSecret = $_ENV['JWT_SECRET'];
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $req = $request->getBody()->getContents();
            $data = json_decode($req, true);

            $name = $data['name'];
            $password = $data['password'];
            $email = $data['email'];

            if(!$name || !$password || !$email)
            {
                $data = [
                    'message' => 'Something Went Wrong',
                    'error' => 'Missing Fields',
                    'status' => 400,
                    'data' => []
                ];

                $response = $response->withHeader('Content-type','application/json');
                $response = $response->withStatus($data['status']);
                $response->getBody()->write(json_encode($data));
                return $response;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $db = DB::connect();

            $q = "INSERT INTO users (`name`, email, `password`, role_id) VALUES (:name, :email, :password, :role_id)";
            $stmt = $db->prepare($q);
            $stmt->execute([
                "name" => $name,
                "email" => $email,
                "password" => $hashed_password,
                "role_id" => 1
            ]);
            $id = $db->lastInsertId();

            $data = [
                "message" => "Success",
                "status" => 201,
                "data"=> $id
            ];

            $response = $response->withHeader('Content-type','application/json');
            $response = $response->withStatus($data['status']);
            $response->getBody()->write(json_encode($data));
            return $response;

        } catch (\Exception $e) {
           $data = [
            'message' => "Something Went Wrong",
            'error' => $e->getMessage(),
            'status' => $e->getCode(),
            'line' => $e->getLine(),
            'data' => []
           ];

           $response = $response->withHeader('Content-type','application/json');
           $response = $response->withStatus(500);
           $response->getBody()->write(json_encode($data));
           return $response;
        }
    }

    public function login(Request $request, Response $response): Response
    {
        try {
            $req = $request->getBody()->getContents();
            $data = json_decode($req, true);
            
            $password = $data['password'] ?? null; 
            $email = $data['email'] ?? null;

            if(!$email || !$password)
            {
                $data = [
                    'message' => 'Something Went Wrong',
                    'error' => 'Missing Fields',
                    'status' => 400,
                    'data' => []
                ];

                $response = $response->withHeader('Content-type','application/json');
                $response = $response->withStatus($data['status']);
                $response->getBody()->write(json_encode($data));
                return $response;
            }

            $db = DB::connect();

            $q = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($q);
            $stmt->execute([
                "email" => $email
            ]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            

            if(!$user || !password_verify($password, $user["password"]))
            {
                $data = [
                    'message' => 'Something Went Wrong',
                    'error' => 'Invalid Credentials',
                    'status' => 401,
                    'data' => []
                ];

                $response = $response->withHeader('Content-type','application/json');
                $response = $response->withStatus($data['status']);
                $response->getBody()->write(json_encode($data));
                return $response;
            }

            $payload = [
                'sub' => $user['id'],
                'email' => $user['email'],
                'iat' => time(),
                'exp' => time() + 3600
            ];

            $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');
           
            $refreshToken = bin2hex(random_bytes(64));
            $expiry = date('Y-m-d H:i:s', time() + $this->refreshTokenExp);

            $q = "UPDATE users SET refresh_token = :token, refresh_token_expiry = :expiry WHERE id = :id";
            $stmt = $db->prepare($q);
            $stmt->execute([
                ":token" => $refreshToken,
                ":expiry" => $expiry,
                ":id" => $user["id"]
            ]);



            $data = [
                'message' => "Login Successfull",
                'status' => 200,
                'token' => $jwt,
                'user_id' => $user["id"],
                'refresh_token' => $refreshToken,
                'expires_in' => $this->accessTokenExp
            ];

           $response = $response->withHeader('Content-type','application/json');
           $response = $response->withStatus($data['status']);
           $response->getBody()->write(json_encode($data));
           return $response;

        } catch (\Exception $e) {
            $data = [
                'message' => "Something Went Wrong",
                'error' => $e->getMessage(),
                'status' => $e->getCode(),
                'line' => $e->getLine(),
                'data' => []
           ];

           $response = $response->withHeader('Content-type','application/json');
           $response = $response->withStatus(500);
           $response->getBody()->write(json_encode($data));
           return $response;
        }
    }

    public function refresh(Request $request, Response $response): Response
    {
        $req = $request->getBody()->getContents();
        $data = json_decode($req, true);

        $refreshToken = $data['refreshToken'] ?? null;

        if(!$refreshToken)
        {
            $data = [
                'message' => 'Something Went Wrong',
                'error' => 'Refresh Token Expired',
                'status' => 400,
                'data' => []
            ];

            $response = $response->withHeader('Content-type','application/json');
            $response = $response->withStatus($data['status']);
            $response->getBody()->write(json_encode($data));
            return $response;
        }

        $db = DB::connect();

        $q = "SELECT * FROM users WHERE refresh_token = :token";
        $stmt = $db->prepare($q);
        $stmt->execute([
            ':token' => $refreshToken
        ]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(!$user)
        {
            $data = [
                'message' => 'Something Went Wrong',
                'error' => 'Invalid or Expired refresh Token',
                'status' => 400,
                'data' => []
            ];

            $response = $response->withHeader('Content-type','application/json');
            $response = $response->withStatus($data['status']);
            $response->getBody()->write(json_encode($data));
            return $response;
        }

        $payload = [
            'sub' => $user['id'],
            'email' => $user['email'],
            'iat' => time(),
            'exp' => time() + $this->accessTokenExp
        ];

        $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');
           
        $refreshToken = bin2hex(random_bytes(64));
        $expiry = date('Y-m-d H:i:s', time() + $this->refreshTokenExp);

        $q = "UPDATE users SET refresh_token = :token, refresh_token_expiry = :expiry WHERE id = :id";
        $stmt = $db->prepare($q);
        $stmt->execute([
            ":token" => $refreshToken,
            ":expiry" => $expiry,
            ":id" => $user["id"]
        ]);



        $data = [
            'message' => "Token Refreshed",
            'status' => 200,
            'token' => $jwt,
            'refresh_token' => $refreshToken,
            'expires_in' => $this->accessTokenExp
        ];

        $response = $response->withHeader('Content-type','application/json');
        $response = $response->withStatus($data['status']);
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}