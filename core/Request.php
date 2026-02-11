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
        
    }
}