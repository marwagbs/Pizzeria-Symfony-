<?php
// src/Controller/ContactController.php
//Dans l'espace de nom App/Controller
namespace App\Controller;

use App\Entity\Product; 
//On utilise Response
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Notre class doit toujours être l'extend de AbstractController
class MenuController extends AbstractController
{
    public function menu(): Response
    {
        // getDoctrine -> méthode pour communiquer avec la BDD
        $product = $this->getDoctrine()
        // Repository = Model (MVC)
        ->getRepository(Product::class)
        // Equivalent de fetchAll() --> récupère tous nos produits de la table product
        ->findAll();
        // On retourne notre template (view)
        return $this->render('menu.html.twig', ["products"=>$product]);
    }
}