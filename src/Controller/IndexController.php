<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Controller\ProductController;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;

class IndexController extends AbstractController
{   
    protected $requestStack;
    
    protected $productRepository;
    
    /**
     * @var Security
     */
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $productRepository = $this->getDoctrine()->getManager()->getRepository(Product::class);
        $products = $productRepository->findAll();
        $user = $this->isLoggedIn();
        
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'products' => $products,
            'user' => $user
        ]);
    }
    
    public function isLoggedIn() {
        //$session = $this->requestStack->getCurrentRequest()->getSession();
        
        $user = $this->security->getUser();
        if($user) {
            return $user;
        }

        return false;
    }
}
