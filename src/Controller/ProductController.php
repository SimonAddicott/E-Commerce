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
     * @Route("/product", name="products", methods={"GET"})
     */
    public function viewProducts(): Response
    {
        
        $products = $this->getProducts();
        
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products
        ]);
    }
    
    /**
     * @Route("/product/{productId}", methods={"GET"})
     */
    public function viewProduct(int $productId): Response
    {
        
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($productId);
        $product = $this->formatProduct($product);
        
        return $this->render('product/product.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product
        ]);
    }
    
    public function getProducts() {
        $repository=$this->getDoctrine()->getManager()->getRepository(Product::class);
        if(!$repository) {
            throw new \Exception("database Repository now available");
        }
        $products=$repository->findAll();
        foreach($products as $product) {
            $output[] = $this->formatProduct($product);
        }
        return $output;
    }
    
    public function getProduct(int $productId) 
    {
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($productId);
        $product = $this->formatProduct($product);
        return $product;
    }
    
    private function formatProduct($product) {
        $productReturn["id"] = $product->getId();
        $productReturn["name"] = $product->getName();
        $productReturn["price"] = $product->getPrice();
        $productReturn["quantity"] = $product->getQuantity();
        $productReturn["image"] = $product->getImage();
        return $productReturn;
    }
    
    public function updateProduct($productId, $params) {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Product::class);
        
        $product = $repository->find($productId);
        
        if ($params['name'] != $product->getName() && !empty($params['name'])) {
            $product->setName($params['name']);
        }
        if ($params['price'] != $product->getPrice() && !empty($params['price'])) {
            $product->setPrice($params['price']);
        }
        if ($params['quantity'] != $product->getQuantity() && !empty($params['quantity'])) {
            $product->setQuantity($params['quantity']);
        }
        if ($params['image'] != $product->getImage() && !empty($params['image']) ) {
            $product->setImage($params['image']);
        }
        
        $entityManager->persist($product);
       
        $entityManager->flush();
        
        // how to check for errors ?
        return true;
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
    
    public function delistProduct($productId, $quantity)
    {
        $product = $this->getProduct($productId);
        if ($product['quantity'] >= $quantity)
        {
            $updatedStockQuantity = $product['quantity'] - $quantity;
            $product['quantity'] = $updatedStockQuantity;
            $this->updateProduct($productId, $product);
            
        } else {
            throw new \Exception('Attempting to delist more stock than we have. ' 
                . print_r([
                    'quantity_held' => $product['quantity'],
                    'quantity_delisting' => $quantity
                ]));    
        }
        
    }
}
