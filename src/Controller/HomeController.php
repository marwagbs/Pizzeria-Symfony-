<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function home(): Response
    {
        $titre = "Page contact";
        return $this->render('index.html.twig', [
            "titre"=>$titre
        ]);
    }
}