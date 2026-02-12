<?php

namespace app\core;

class Application
{
    public static string $ROOT_DIR; // Apsolutna putanja do root direktorija projekta (koristi se globalno npr. za učitavanje view-ova/fajlova).
    public Router $router; // Glavni router aplikacije (mapira URL rute na callback/controller).
    public Request $request; // Trenutni HTTP zahtjev (čuva path i metodu zahtjeva).
    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;                 // Postavlja root direktorij projekta (obično __DIR__ iz index.php).
        $this->request = new Request();              // Kreira objekat Request za čitanje podataka o trenutnom zahtjevu.
        $this->router = new Router($this->request);  // Kreira Router i prosljeđuje mu Request da može match-ovati rute.
    }

    public function run()
    {
        echo $this->router->resolve(); // Pokreće obradu zahtjeva: Router pronalazi i izvršava odgovarajuću rutu.
    }
}