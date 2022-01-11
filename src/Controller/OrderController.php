<?php
// src/Controller/ContactController.php
//Dans l'espace de nom App/Controller
namespace App\Controller;


//On utilise Response
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderProduct;  
use App\Form\ProductType; //On utilise Response 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\String\Slugger\SluggerInterface; 
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;

class OrderController extends AbstractController
{
   public function order() 
   {
    //on a une commande
    if(empty($this->getUser())){
        return $this->render('security/login.html.twig');
    }  
    $order=new Order();
       $order->setDate(new \DateTime());
       $order->setUser($this->getUser());
       $order->setTva(10);
       $order->setTotal(40);
       
        $order->setDiscount(5);
        $order->setDelivery(10);
    //on le registre dans la base 
    $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
    $session=new Session();
   
     //on va parcourir notre cart et on va enregistrer chaque commande dans la base   
    foreach($session->get('cart') as $id=>$quantity){
        $ligne=new OrderProduct();
        $ligne->setQuantity($quantity);
        $ligne->setIdOrder($order);
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);
        $ligne->setIdProduct($product);
        $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ligne);
            $entityManager->flush();
    }
    
    return $this->render('index.html.twig');
   }

}
