<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    /**
     * @Route("/basket/{basketId}", name="basket", methods={"GET"})
     */
    public function index($basketId): Response
    {
        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController',
        ]);
    }
    
}
