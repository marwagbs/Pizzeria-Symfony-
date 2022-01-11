<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class ContactController
{
    public function contact(): Response
    {
        echo "BONJOUR";
        return new Response(
            '<html><h1>Bienvenue dans la page Contact </h1></html>'
        );
    }
    public function mail(): Response
    {
        return new Response(
            '<html><h1>Bienvenue dans la page Contact </h1></html>'
        );
    }
}