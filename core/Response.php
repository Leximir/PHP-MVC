<?php

namespace app\core;

class Response
{

    public function setStatusCode(int $code)
    {
        http_response_code($code); // Postavlja HTTP statusni kod odgovora (npr. 200, 404, 500) koji server šalje browseru.
    }

}