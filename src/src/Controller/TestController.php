<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function test(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}