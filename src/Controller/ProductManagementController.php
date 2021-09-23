<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductManagementController extends AbstractController
{
    /**
     * @Route("/admin/product/{productId}", name="product_management", methods={"GET"})
     */
    public function viewProduct(ProductController $productController, int $productId): Response
    {
        $product = $productController->getProduct($productId);
        return $this->render('product_management/index.html.twig', [
            'controller_name' => 'ProductManagementController',
            'product' => $product,
            'error' => ''
        ]);
    }
    
    /**
    * @Route("/admin/product/{productId}", name="update_management", methods={"POST"})
    */
    public function updateProduct(ProductController $productController, int $productId, Request $request): Response
    {
        $product = $productController->getProduct($productId);
        
        $product['name'] = $request->get('name');
        $product['price'] = $request->get('price');
        $product['quantity'] = $request->get('quantity');
        
        $error = '';
        if (!$productController->updateProduct($productId, $product)) {
            $error = '<h1>Unable to update product</h1>';
        }
        return $this->render('product_management/index.html.twig', [
            'controller_name' => 'ProductManagementController',
            'product' => $product,
            'error' => $error,
        ]);
        
    }
}
