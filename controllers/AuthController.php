<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\models\RegisterModel;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        if($request->isGet()){
            $this->setLayout('auth');
            return $this->render('login');
        }

        if($request->isPost()){

        }
    }

    public function register(Request $request)
    {
        $registerModel = new RegisterModel();
        $this->setLayout('auth');

        if($request->isGet()){
            return $this->render('register', [
                'model' => $registerModel,
            ]);
        }

        if($request->isPost()){

            $registerModel->loadData($request->getBody());

            if($registerModel->validate() && $registerModel->register()){
                return "Success";
            }

            return $this->render('register', [
                'model' => $registerModel,
            ]);
        }
    }

}