<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Basket;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\HttpFoundation\RequestStack;

class BasketController extends AbstractController
{
    
    private $requestStack;
    
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    
    /**
     * @Route("/basket", name="add_to_basket", methods={"POST"})
     */
    public function addToBasket(Request $request, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $productId = $request->get('product_id');
        $csrfToken = $request->get('_csrf_token');
        
        $token = new CsrfToken('authenticate', $csrfToken);
        if (!$csrfTokenManager->isTokenValid($token)) 
        {
            throw new InvalidCsrfTokenException();
        }
        
        $session = $this->requestStack->getCurrentRequest()->getSession();
        
        $basket = $session->get('basket');
            
        if(isset($basket["$productId"]))
        { 
            $basket["$productId"]++;
        } else {
            $basket["$productId"] = 1;
        }
        
        if(isset($basket['total']))
        {
            $basket['total']++;
        } else {
            $basket['total'] = 1;
        }
            
        $session->set('basket', $basket);
        
        $url = $request->headers->get('referer');
        if(!preg_match('/added/', $url)){
            $url .= "?added";
        }
        
        return $this->redirect($url, 303);
    }
    
}
