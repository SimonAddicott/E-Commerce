<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;

class CheckoutController extends AbstractController
{
    private $requestStack;
    
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    
    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(): Response
    {
        $checkout = [];
        $session = $this->requestStack->getCurrentRequest()->getSession();
        
        $productRepository = $this->getDoctrine()->getManager()->getRepository(Product::class);
        
        if($session->has('basket')) { 
            $basket = $session->get('basket');
            $basketTotal = 0;
            foreach ($basket as $productId => $quantity)
            {
                if(!is_numeric($productId))
                    continue;
                
                $productData = $productRepository->find((int)$productId);
                
                if(!$productData)
                    return $this->render('error_page.html.twig', ['error' => 'product not found - ' . $productId]);
                
                $itemIotal = $productData->getPrice() * (int)$quantity;
                $basketTotal += $itemIotal;
                
                $checkoutItem = [
                    'product' => $productData,
                    'quantity' => $quantity,
                    'item_total' => $itemIotal
                ];
                $checkout['products'][] = $checkoutItem;
            }
            $checkout['basket_total'] = $basketTotal;
            
        }
        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
            'basket' => $checkout
        ]);
    }
}
