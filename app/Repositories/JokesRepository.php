<?php

declare(strict_types= 1);

namespace App\Repositories;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Config\DB as DB;

class JokesRepository
{
    public function __construct()
    {}

    public function get(Request $request, Response $response, $args): Response{
        try {
            
            $user_id = $args["user_id"] ?? null;

            if(!$user_id) {
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

            $q = "SELECT * FROM users WHERE id = :user_id";
            $stmt = $db->prepare($q);
            $stmt->execute([
                'user_id' => $user_id
            ]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if(!$user) {
                $data = [
                    'message' => 'Something Went Wrong',
                    'error' => 'User Does not Exist',
                    'status' => 400,
                    'data' => []
                ];

                $response = $response->withHeader('Content-type','application/json');
                $response = $response->withStatus($data['status']);
                $response->getBody()->write(json_encode($data));
                return $response;
            }

            $q = "SELECT * FROM jokes WHERE `user_id` = :user_id";
            $stmt = $db->prepare($q);
            $stmt->execute([
                'user_id' => $user_id
            ]);
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $data = [
                "message" => "Success",
                "status" => 200,
                "data"=> $data
            ];

            $response = $response->withHeader('Content-type','application/json');
            $response->withStatus($data['status']);
            $response->getBody()->write(json_encode($data));
            return $response;
        }
        catch (\Exception $e) {
           $data = [
            'message' => "Something Went Wrong",
            'error' => $e->getMessage(),
            'status' => $e->getCode(),
            'line' => $e->getLine(),
            'data' => []
           ];

           $response = $response->withHeader('Content-type','application/json');
           $response->withStatus($data['status']);
           $response->getBody()->write(json_encode($data));
           return $response;
        }
    }

    public function create(Request $request, Response $response, $args): Response
    {
        try {

            $req = $request->getBody()->getContents();
            $data = json_decode($req, true);

            $user_id = $data['user_id'] ?? null;
            $joke_id = $data['joke_id'] ?? null;
            $text = $data['text'] ?? null;

            if(!$user_id || !$joke_id || !$text)
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

            $q = "SELECT * FROM jokes WHERE joke_id = :joke_id";
            $stmt = $db->prepare($q);
            $stmt->execute([
                'joke_id' => $joke_id
            ]);
            $joke = $stmt->fetch(\PDO::FETCH_ASSOC);

            if($joke)
            {
                $data = [
                    'message' => 'Success',
                    'status' => 200,
                    'data' => []
                ];

                $response = $response->withHeader('Content-type','application/json');
                $response = $response->withStatus($data['status']);
                $response->getBody()->write(json_encode($data));
                return $response;
            }

            $q = "INSERT INTO jokes (joke_id, `text`, user_id) VALUES (:joke_id, :text, :user_id)";
            $stmt = $db->prepare($q);
            $stmt->execute([
                'joke_id' => $joke_id,
                'text' => $text,
                'user_id' => $user_id
            ]);

            $id = $db->lastInsertId();

            $data = [
                "message" => "Success",
                "status" => 201,
                "data"=> $id
            ];

            $response = $response->withHeader('Content-type','application/json');
            $response->withStatus($data['status']);
            $response->getBody()->write(json_encode($data));
            return $response;
        }
        catch (\Exception $e) {
           $data = [
            'message' => "Something Went Wrong",
            'error' => $e->getMessage(),
            'status' => $e->getCode(),
            'line' => $e->getLine(),
            'data' => []
           ];

           $response = $response->withHeader('Content-type','application/json');
           $response->withStatus($data['status']);
           $response->getBody()->write(json_encode($data));
           return $response;
        }
    }

    public function like(Request $request, Response $response, $args): Response
    {
        try {
            $req = $request->getBody()->getContents();
            $data = json_decode($req, true);

            $joke_id = $data['joke_id'] ?? null;
            $user_id = $data['user_id'] ?? null;

            if(!$user_id || !$joke_id)
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

            $q = "UPDATE jokes SET favourite = :favourite WHERE joke_id = :joke_id";
            $stmt = $db->prepare($q);
            $stmt->execute([
                ':favourite' => 1,
                ":joke_id" => $joke_id
            ]);
            $rowCount = $stmt->rowCount();

            $data = [
                "message" => "Success",
                "status" => 200,
                "data"=> $rowCount
            ];

            $response = $response->withHeader('Content-type','application/json');
            $response->withStatus($data['status']);
            $response->getBody()->write(json_encode($data));
            return $response;
        }
        catch (\Exception $e) {
           $data = [
            'message' => "Something Went Wrong",
            'error' => $e->getMessage(),
            'status' => $e->getCode(),
            'line' => $e->getLine(),
            'data' => []
           ];

           $response = $response->withHeader('Content-type','application/json');
           $response->withStatus($data['status']);
           $response->getBody()->write(json_encode($data));
           return $response;
        }
    }
}