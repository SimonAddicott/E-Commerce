<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductManagementController extends AbstractController
{
    /**
     * @Route("/admin/product/{productId}", name="product_management")
     */
    public function index(ProductController $productController, int $productId): Response
    {
        $product = $productController->getProduct($productId);
        return $this->render('product_management/index.html.twig', [
            'controller_name' => 'ProductManagementController',
            'product' => $product
        ]);
    }
}
