<?php

use app\core\Application;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../functions.php";

$app = new Application(dirname(__DIR__)); // Kreira instancu aplikacije i postavlja root direktorij projekta; tokom inicijalizacije kreira Request i Router i povezuje ih da bi aplikacija mogla prepoznati trenutni URL i izvršiti odgovarajuću rutu.

$app->router->get('/', 'home');

$app->router->get('/contact', 'contact');

$app->run(); // Pokretanje aplikacije: router pokušava da match-uje rutu i izvrši odgovarajući handler