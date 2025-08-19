<?php
 
 declare(strict_types= 1);

//  ini_set('display_errors',1);
//  error_reporting(E_ALL);


 use Psr\Http\Message\ResponseInterface as Response;
 use Psr\Http\Message\ServerRequestInterface as Request;
 use App\Repositories;
 use Slim\App;
 use App\Middleware;

 return function (App $app) {
   $jwtMiddleware = new Middleware\JwtMiddleware();
   
    $app->post('/api/v1/register', [Repositories\UserRepository::class, 'create']);
    $app->post('/api/v1/login', [Repositories\UserRepository::class,'login']);
    $app->post('/api/v1/refresh', [Repositories\UserRepository::class,'refresh']);
    
    $app->get('/api/v1/joke/{user_id}', [Repositories\JokesRepository::class, 'get'])->add($jwtMiddleware);
    $app->post('/api/v1/joke', [Repositories\JokesRepository::class,'create'])->add($jwtMiddleware);
    $app->patch('/api/v1/joke', [Repositories\JokesRepository::class, 'like'])->add($jwtMiddleware);
 };