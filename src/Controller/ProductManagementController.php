<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PHPUnit\Util\FileLoader;

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
            'error' => 'SHOW'
        ]);
    }
    
    /**
    * @Route("/admin/product/{productId}", name="update_management", methods={"POST"})
    */
    public function updateProduct(ProductController $productController, int $productId, Request $request): Response
    {
        $product = $productController->getProduct($productId);
        
        $productUpdate = $product;
        $productUpdate['name'] = $request->get('name');
        $productUpdate['description'] = $request->get('description');
        $productUpdate['price'] = $request->get('price');
        $productUpdate['quantity'] = $request->get('quantity');
        
        if ($request->files->get('image') != ''){
            $file = $request->files->get('image');
            
            $uploadDir = '/tmp';
            
            $filename = hash('md5', $file->getClientOriginalName());
            $filePath = $uploadDir . '/' . $filename;
            
            $file->move($uploadDir, $filename);
            $encodedImage = base64_encode(file_get_contents($filePath));
            unlink($filePath);
            
            $productUpdate['image'] = $encodedImage;
        }
        
        $error = 'OK';
        if (!$productController->updateProduct($productId, $productUpdate)) {
            $error = '<h1>Unable to update product</h1>';
        }
        
        $product = $productController->getProduct($productId);
        return $this->render('product_management/index.html.twig', [
            'controller_name' => 'ProductManagementController',
            'product' => $product,
            'error' => $error
        ]);
        
    }
}
