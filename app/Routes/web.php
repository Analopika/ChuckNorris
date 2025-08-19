<?php
 
 declare(strict_types= 1);

 ini_set('display_errors',1);
 error_reporting(E_ALL);


 use Psr\Http\Message\ResponseInterface as Response;
 use Psr\Http\Message\ServerRequestInterface as Request;
 use App\Middleware;
 use Slim\App;
 use Slim\Views\Twig;

 return function (App $app, Twig $twig) {

    
    $app->get('/', function(Request $request, Response $response, $args) use ($twig) {
        return $twig->render($response, 'login.twig');
    });

    $app->get('/register', function(Request $request, Response $response, $args) use ($twig) {
        return $twig->render($response, 'register.twig');
    });
    
    $app->get('/home', function(Request $request, Response $response, $args) use ($twig) {
        return $twig->render($response, 'home.twig');
    });

    $app->get('/favourites', function(Request $request, Response $response, $args) use ($twig) {
        return $twig->render($response, 'favourite.twig');
    });
 };