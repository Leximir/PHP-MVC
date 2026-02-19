<?php

namespace app\core;

class Application
{
    public static string $ROOT_DIR;       // Apsolutna putanja do root direktorija projekta (koristi se globalno npr. za učitavanje view-ova/fajlova).
    public Router $router;                // Glavni router aplikacije (mapira URL rute na callback/controller).
    public Request $request;              // Trenutni HTTP zahtjev (čuva informacije poput path-a i HTTP metode).
    public Response $response;            // HTTP odgovor aplikacije (npr. postavljanje status koda, redirect, header-i).
    public Database $db;
    public static Application $app;       // Globalna referenca na trenutno pokrenutu aplikaciju (lako dostupna iz drugih klasa).
    public Controller $controller;
    public function __construct($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;                 // Postavlja root direktorij projekta (obično __DIR__ iz index.php).
        self::$app = $this;                          // Sprema trenutnu instancu aplikacije u static property radi globalnog pristupa.
        $this->request = new Request();              // Kreira objekat Request za čitanje podataka o trenutnom zahtjevu.
        $this->response = new Response();            // Kreira objekat Response za upravljanje HTTP odgovorom (status kod, header-i).
        $this->router = new Router($this->request, $this->response);  // Kreira Router i prosljeđuje mu Request da može match-ovati rute i Response.
        $this->db = new Database($config['db']);
    }

    public function run()
    {
        echo $this->router->resolve(); // Pokreće obradu zahtjeva: Router pronalazi i izvršava odgovarajuću rutu.
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }
}