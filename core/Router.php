<?php

namespace app\core;

class Router
{
    public Request $request; // Čuva Request objekat (trenutni HTTP zahtjev) da Router može čitati path i metodu.
    protected array $routes = []; // Mapa ruta: čuva definisane rute (path -> callback) po HTTP metodi.

    public function __construct(Request $request)
    {
        $this->request = $request; // Prima i sprema Request objekat koji Router koristi za čitanje path/metode.
    }
    public function get($path, $callback) // Registruje GET rutu: povezuje URL putanju sa callback funkcijom.
    {
        $this->routes['get'][$path] = $callback; // Sprema callback pod datom putanjom u GET rute.
    }

    public function resolve()
    {
        $this->request->getPath(); // Uzima trenutni path i na osnovu njega pronalazi i izvršava odgovarajuću rutu.
    }

}