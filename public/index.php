<?php

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../functions.php";

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];

$app = new Application(dirname(__DIR__), $config); // Kreira instancu aplikacije i postavlja root direktorij projekta; tokom inicijalizacije kreira Request i Router i povezuje ih da bi aplikacija mogla prepoznati trenutni URL i izvršiti odgovarajuću rutu.

$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'index']);
$app->router->post('/contact', [SiteController::class, 'handleContact']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->run(); // Pokretanje aplikacije: router pokušava da match-uje rutu i izvrši odgovarajući handler