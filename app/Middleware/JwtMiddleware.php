<?php

declare(strict_types=1);

namespace App\Middleware;
//  ini_set('display_errors',1);
//  error_reporting(E_ALL);
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware
{
    private string $secret;

    public function __construct()
    {
        $this->secret = $_ENV['JWT_SECRET'];
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeader('Authorization');

        if (!$authHeader) {
            $data = [
                'message' => 'Something Went Wrong',
                'error' => 'Authorization Header Missing',
                'status' => 401,
                'data' => []
            ];

            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode($data));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($data['status']);
        }

        try {
            $token = trim(str_replace('Bearer', '', $authHeader[0]));
            
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            $request = $request->withAttribute('user', (array) $decoded);

            return $handler->handle($request);
        } catch (\Exception $e) {
            $data = [
                'message' => 'Something Went Wrong',
                'error' => 'Invalid or Expired Token',
                'status' => 401,
                'data' => []
            ];

            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode($data));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($data['status']);
        }
    }
}