<?php

use app\core\Application;

require_once __DIR__."/vendor/autoload.php";
require_once "functions.php";

$app = new Application(); // Kreiranje instance aplikacije (inicijalizuje router, request/response, config, itd.)

$app->router->get('/', function(){
    return "Hello World";
});

$app->router->get('/contact', function(){
    return "Contact";
});

$app->run(); // Pokretanje aplikacije: router pokušava da match-uje rutu i izvrši odgovarajući handler