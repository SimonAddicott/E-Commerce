<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\ProductController;
use Symfony\Component\HttpFoundation\RequestStack;

class IndexController extends AbstractController
{   
    protected $requestStack;
    
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $products = $this->forward('App\Controller\ProductController::getProducts');
        $isLoggedIn = $this->isLoggedIn();
        
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'products' => $products,
            'is_logged_in' => $isLoggedIn
        ]);
    }
    
    public function isLoggedIn() {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $user = $session->get('User');
        if($user) {
            return true;
        }

        return false;
    }
}
