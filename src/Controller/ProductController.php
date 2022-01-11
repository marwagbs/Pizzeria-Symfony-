<?php
// src/Controller/ContactController.php
//Dans l'espace de nom App/Controller
namespace App\Controller;

use App\Entity\Product; 
use App\Form\ProductType;
//On utilise Response
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
// Notre class doit toujours être l'extend de AbstractController
class ProductController extends AbstractController
{
    public function addProduct(Request $request, SluggerInterface $slugger): Response
    {
    $product = new Product();
    //creation du form
    $form = $this->createForm(ProductType::class, $product);
    //récuperation du donné
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
            // but, the original `$product` variable has also been updated
        $product = $form->getData();
        $photoFile = $form->get('photo')->getData();
        // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
        // return $this->redirectToRoute('task_success');
        if ($photoFile) {
            $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $photoFile->move(
                    $this->getParameter('photo_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $product->setPhoto($newFilename);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
        }

        // ... persist the $product variable or any other work
        return $this->redirectToRoute('menu');
    }

   //affichage de la page
    return $this->render('product/product.html.twig', [
        'form' => $form->createView(),
    ]);

    }

    /**
     * @Route("/removeProduct/{id}", name="remove")
     */
    public function removeProduct(int $id): Response
    { 
    //supprimer des products
    $repository = $this->getDoctrine()->getRepository(Product::class);
    $product = $repository->find($id);
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($product);
    $entityManager->flush();

    return $this->redirectToRoute('menu');
    }


}
