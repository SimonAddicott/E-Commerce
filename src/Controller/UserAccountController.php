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
}
