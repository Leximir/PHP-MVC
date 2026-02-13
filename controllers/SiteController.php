<?php

namespace app\controllers;

use app\core\Application;

class SiteController
{
    public function home()
    {
        $params = [
            'name' => 'Aleksa',
        ];

        return Application::$app->router->renderView('home', $params);
    }
    public function index()
    {
        return Application::$app->router->renderView('contact');
    }
    public function handleContact()
    {
        return "Handling data";
    }
}