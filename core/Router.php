<?php

namespace app\core;

class Router
{
    public Request $request;      // Čuva Request objekat (trenutni HTTP zahtjev) da Router može čitati path i metodu.
    protected array $routes = []; // Mapa ruta: čuva definisane rute (path -> callback) po HTTP metodi.
    public Response $response;    // Cuva Response objekat da ruter ima pristup HTTP odgovorima aplikacije (npr. postavljanje status koda, redirect, header-i).
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request; // Prima i sprema Request objekat koji Router koristi za čitanje path/metode.
        $this->response = $response; // Prima i sprema Response objekat da Router može postaviti status kod ili raditi redirect.
    }
    public function get($path, $callback) // Registruje GET rutu: povezuje URL putanju sa callback funkcijom.
    {
        $this->routes['get'][$path] = $callback; // Sprema callback pod datom putanjom u GET rute.
    }

    public function post($path, $callback) // Registruje POST rutu: povezuje URL putanju sa callback funkcijom.
    {
        $this->routes['post'][$path] = $callback; // Sprema callback pod datom putanjom u POST rute.
    }

    protected function layoutContent()
    {
        ob_start(); // Uključuje output buffering: sve što se "ispisuje" ide u buffer umjesto na ekran.
        include_once Application::$ROOT_DIR."/views/layouts/main.view.php"; // Učitava layout (glavni šablon: header/footer + {{ content }} placeholder).
        return ob_get_clean(); // Vraća sadržaj buffera kao string i gasi buffer.
    }

    protected function renderOnlyView($view, $params)
    {
        // Pretvara $params niz u pojedinačne varijable dostupne unutar view fajla.
        // Primjer: ['title' => 'Kontakt'] postaje $title = 'Kontakt' u view-u.
        foreach ($params as $key => $value){
            $$key = $value; // "Variable variables": kreira varijablu čije ime je u $key.
        }

        ob_start();  // Uključuje output buffering: sve što se "ispisuje" ide u buffer umjesto na ekran.
        include_once Application::$ROOT_DIR."/views/$view.view.php"; // Učitava konkretan view fajl (npr. "contact" -> views/contact.view.php).
        return ob_get_clean(); // Vraća sadržaj buffera kao string i gasi buffer.
    }
    public function renderView($view, $params = [])
    {
        $layoutContent = $this->layoutContent(); // Učita layout kao string (sadrži {{ content }} mjesto za ubacivanje view-a).
        $viewContent = $this->renderOnlyView($view, $params); // Učita view kao string i ubaci mu proslijeđene parametre kao varijable.
        return str_replace("{{ content }}", $viewContent, $layoutContent); // U layoutu zamijeni {{ content }} sa view sadržajem i vrati finalni HTML.
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent(); // Učita layout kao string (sadrži {{ content }} mjesto za ubacivanje view-a).
        return str_replace("{{ content }}", $viewContent, $layoutContent);
    }

    public function resolve()
    {
        $path = $this->request->getPath(); // Uzima trenutni path i na osnovu njega pronalazi i izvršava odgovarajuću rutu.
        $method = $this->request->getMethod(); // Čita HTTP metodu (get/post) da bi se birala ruta za tu metodu.
        $callback = $this->routes[$method][$path] ?? false; // Traži registrovanu rutu za dati path i metodu; ako ne postoji vraća false.

        if($callback === false){ // Ako ruta nije pronađena, vraćamo 404 poruku i prekidamo izvršavanje.
            $this->response->setStatusCode(404);
            return $this->renderView("_404");
        }

        if(is_string($callback)){
            return $this->renderView($callback);
        }

        if(is_array($callback)){                            // Provjerava da li je callback definisan kao [KlasaKontrolera, 'metoda'].
            $callbackClass = $callback[0];                  // Uzima naziv klase kontrolera (npr. SiteController::class).
            $callbackMethod = $callback[1];                 // Uzima ime metode koja se poziva na kontroleru (npr. 'handleContact').
            $callbackObject = new $callbackClass();         // Kreira instancu kontrolera (metoda nije statička pa mora objekat).
            $callback = [$callbackObject, $callbackMethod]; // Kreira instancu kontrolera (metoda nije statička pa mora objekat).
        }

        return call_user_func($callback, $this->request); // Poziva callback i prosljeđuje Request objekat (da handler može čitati body, path, metodu...).
    }

}