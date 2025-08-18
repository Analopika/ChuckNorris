<?php

declare(strict_types=1);
\date_default_timezone_set('Africa/Johannesburg');
\session_cache_limiter(null);
\session_start();

 ini_set('display_errors',1);
 error_reporting(E_ALL);
use Slim\Factory\AppFactory;
use Twig\Loader\FilesystemLoader;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;


require __DIR__ . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$app = AppFactory::create();



$fileSystemLoader = new FilesystemLoader();

$fileSystemLoader = new FilesystemLoader(__DIR__ . '/../app/Views');
$fileSystemLoader->addPath(__DIR__ . '/../app/Views/components', 'components');



$twig = new Twig($fileSystemLoader, [
    'cache' => $_ENV['ENV'] == 'prod' ? true : false,
    'debug' => $_ENV['ENV'] == 'prod' ? false : false,
]);

$twig->addExtension(new \Twig\Extension\DebugExtension());
// $twig->addExtension(new Helpers\MarkdownExtension());

$app->add(TwigMiddleware::create($app, $twig));

$router = require __DIR__ . '/../app/Routes/web.php';

$router($app, $twig);

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
// $errorMiddleware->setDefaultErrorHandler([ExceptionLogging::class, 'exceptionLoggingHandler']);

$app->run();