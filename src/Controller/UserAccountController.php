<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserAccountController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    
    /**
     * @Route("/account", name="user_account")
     */
    public function index(): Response
    {
        $user = $this->security->getUser();
        $orders = [];
        
        return $this->render('user_account/index.html.twig', [
            'controller_name' => 'UserAccountController',
            'user' => $user,
            'orders' => $orders
        ]);
    }
    
    /**
     * @Route("/account/summary", name="user_account_summary")
     */
    public function summary(): Response
    {
        $user = $this->security->getUser();
        $orders = [];
        
        return $this->render('user_account/summary.html.twig', [
            'controller_name' => 'UserAccountController',
            'user' => $user,
            'orders' => $orders
        ]);
    }
    
    /**
     * @Route("/account/purchases", name="user_account_purchases")
     */
    public function purchases(OrderController $orderController): Response
    {
        $user = $this->security->getUser();
        $orders = $orderController->getOrdersByUserId($user->getId());
        
        return $this->render('user_account/purchases.html.twig', [
            'controller_name' => 'UserAccountController',
            'user' => $user,
            'orders' => $orders
        ]);
    }
    
    /**
     * @Route("/account/addresses", name="user_account_addresses")
     */
    public function addresses(): Response
    {
        $user = $this->security->getUser();
        
        
        return $this->render('user_account/addresses.html.twig', [
            'controller_name' => 'UserAccountController',
            'user' => $user
        ]);
    }
    
}
