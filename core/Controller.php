<?php

namespace app\core;

class Controller
{
    public string $layout = "main"; // Naziv layout-a koji controller koristi (default je "main").

    public function render($view, $params = [])
    {
        return Application::$app->router->renderView($view, $params);  // Renderuje zadani view i prosleđuje parametre, koristeći Router-ovu renderView metodu preko globalne Application instance.
    }

    public function setLayout($layout)
    {
        $this->layout = $layout; // Mijenja layout koji će controller koristiti (npr. "auth", "admin", itd.).
    }
}