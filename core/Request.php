<?php

namespace app\core;

class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? "/"; // REQUEST_URI sadrži path + query string (npr. "/contact?x=1")
        $position = strpos($path, '?'); // traži gdje se (na kojoj poziciji / indeksu) prvi put pojavljuje znak ? u stringu $path.

        if($position === false){
            return $path; // nema query string-a
        }

        return substr($path, 0, $position); // uzima dio stringa iz varijable $path, počevši od indeksa 0 (početak stringa), i uzima tačno $position karaktera.
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);// Vraća HTTP metodu zahtjeva (GET/POST...) pretvorenu u mala slova radi lakšeg poređenja u Routeru.
    }

    public function getBody()
    {
        $body = []; // Asocijativni niz u koji skupljamo podatke iz request-a (GET ili POST).

        if ($this->getMethod() === "get") { // Ako je metoda GET, uzimamo parametre iz query string-a ($_GET).
            foreach ($_GET as $key => $value) {
                // Sanitizuje vrijednost GET parametra (escape specijalnih znakova) radi sigurnijeg prikaza/obrade.
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->getMethod() === "post") { // Ako je metoda POST, uzimamo podatke poslane kroz formu ($_POST).
            foreach ($_POST as $key => $value) {
                // Sanitizuje vrijednost POST parametra (escape specijalnih znakova) radi sigurnijeg prikaza/obrade.
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body; // Vraća sanitizovane podatke request-a kao asocijativni niz (ključ => vrijednost).
    }

}