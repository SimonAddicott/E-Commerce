<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $products = $this->forward('App\Controller\ProductController::getProducts');
        
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'products' => $products
        ]);
    }
}
