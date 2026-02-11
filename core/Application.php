<?php

namespace app\core;

class Application
{
    public Router $router; // Glavni router aplikacije (mapira URL rute na callback/controller).
    public Request $request; // Trenutni HTTP zahtjev (čuva path i metodu zahtjeva).
    public function __construct()
    {
        $this->request = new Request();              // Kreira objekat Request za čitanje podataka o trenutnom zahtjevu.
        $this->router = new Router($this->request);  // Kreira Router i prosljeđuje mu Request da može match-ovati rute.
    }

    public function run()
    {
        $this->router->resolve(); // Pokreće obradu zahtjeva: Router pronalazi i izvršava odgovarajuću rutu.
    }
}