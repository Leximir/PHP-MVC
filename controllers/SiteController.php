<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;

class SiteController extends Controller
{
    public function home()
    {
        $params = [
            'name' => 'Aleksa',
        ];

        return $this->render('home', $params);
    }
    public function index()
    {
        return Application::$app->router->renderView('contact');
    }
    public function handleContact()
    {
        $body = Application::$app->request->getBody();
        dd($body);
        return "Handling data";
    }
}