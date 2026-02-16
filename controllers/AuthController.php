<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        if($request->isGet()){
            return $this->render('login');
        }

        if($request->isPost()){

        }
    }

    public function register(Request $request)
    {
        if($request->isGet()){
            return $this->render('register');
        }

        if($request->isPost()){

        }
    }

}