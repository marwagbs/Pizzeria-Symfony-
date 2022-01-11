<?php
// src/Controller/ContactController.php
//Dans l'espace de nom App/Controller
namespace App\Controller;


//On utilise Response
use App\Entity\Product;  
use App\Form\ProductType; //On utilise Response 
use Symfony\Component\HttpFoundation\Response; 
use App\Repository\ProductRepository; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface; 
use Symfony\Component\HttpFoundation\Session\Session;

class CartController extends AbstractController
{
    public function addToCart(int $idProduct): Response
    { 
        //ici on doit démarrer les sessions
        $session = new Session();
        $session->start();

        $sessionCart = $session->get('cart');

        //Si ce qu'on recupère dans la session du cart est complètement vide, alors on ajouter un produit. 
       if (empty($sessionCart)){
           $sessionCart[$idProduct]=1;
           $session->set('cart',$sessionCart);

        //Sinon si dans la session du panier, il y a dejà l'id en question, on incrémente. 
       }else{
           //est ec que la clé id produit existe pas
           if(!array_key_exists($idProduct, $sessionCart)) {
               $sessionCart[$idProduct]=0;
           }
            // S'il est pas vide mais que l'id n'y est pas, on le rajoute.
            // $tab[]= veut dire push dans php
            ++$sessionCart[$idProduct];
            $session->set('cart',$sessionCart);
       }
        exit();
    }
    public function getCount(){

        $session = new Session();
        $session->start();

        $sessionCart = $session->get('cart');
        $total=0;
        foreach ($sessionCart as $element) {
            $total+=$element;
        }
       echo($total);
        exit();           
    }
        
    //AFFICHE LES PRODUITS DANS LE CART
    public function displayCart(): Response
    { 
        $session = new Session();
        $session->start();
        // On récupère les id des produits qu'on stock dans $products.
        $products=$session->get('cart',[]);
        $dataPanier = [];
        $totalPanier = 0;
        foreach($products as $id=>$quantity) {
            $repository = $this->getDoctrine()->getRepository(Product::class);
            // Ici on récupère un produit (un id)
            $product = $repository->find($id);
            //On stock nos produits dans un tableau.
            $dataPanier[]=[
                 "product" => $product,
                 "quantity" => $quantity];
    
            }
            foreach($dataPanier as $item){
                $totalPanier += $item['product']->getPrice() * $item['quantity'];
            }
        return $this->render('cart.html.twig',[
            "dataPanier" => $dataPanier, 
            "totalPanier" =>$totalPanier]);
    }
       /**
     * @Route("/removeProduct/{id}", name="removeCart")
     */  
     public function remove(int $id):Response
     {
        $session = new Session();
        $session->start();
        // On récupère le panier actuel
        $sessionCart = $session->get("cart", []);
        foreach($sessionCart as $id=>$quantity){
        $repository = $this->getDoctrine()->getRepository(Product::class);
             // Ici on récupère un produit (un id)
        $product = $repository->find($id);
        if(!empty($sessionCart[$id])){
            if($sessionCart[$id] > 1){
               $sessionCart[$id]--;
               $session->set("cart", $sessionCart);
            }else{
                unset($sessionCart[$id]);
            }
        }
            // On sauvegarde dans la session
        $session->set("cart", $sessionCart);

        return $this->redirectToRoute("cart");
    }
    }
    /**
     * @Route("/delete/{id}", name="deleteCart")
     */
    public function delete(int $id): Response
    {
        $session = new Session();
        $session->start();
         // On récupère le panier actuel
        $sessionCart = $session->get("cart", []);
        
        $repository = $this->getDoctrine()->getRepository(Product::class);
          // Ici on récupère un produit (un id)
        $product = $repository->find($id);

        if(!empty($sessionCart[$id])){
            unset($sessionCart[$id]);
        }

        // On sauvegarde dans la session
        $session->set("cart", $sessionCart);

        return $this->redirectToRoute("cart");
    }

    // /**
    //  * @Route("/delete", name="delete_all")
    //  */
    // public function deleteAll():Response
    // {    $session = new Session();
    //     $session->start();
        
    //     $session->remove("cart");

    //     return $this->redirectToRoute("cart");
    // }



}
