<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
Use App\Entity\Product;
use App\Repository\ProductRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product", methods={"GET"})
     */
    public function index(): Response
    {
        
        $products = $this->getProducts();
        
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products
        ]);
    }
    
    public function getProducts() {
        $repository=$this->getDoctrine()->getManager()->getRepository(Product::class);
        $products=$repository->findAll();
        foreach($products as $product) {
            $productReturn["name"] = $product->getName();
            $productReturn["price"] = $product->getPrice();
            $productReturn["quantity"] = $product->getQuantity();
            
            $output[] = $productReturn;
        }
        return $output;
    }
    /**
     * @Route("/product", name="create_product", methods={"POST"})
     */
    public function createProduct(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $name = $request->get('name');
        $price = $request->get('price');
        $quantity = $request->get('quantity');
        
        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);
        $product->setQuantity($quantity);
        
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);
        
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        
        return new Response('Saved new product with id '.$product->getId());
    }
}
