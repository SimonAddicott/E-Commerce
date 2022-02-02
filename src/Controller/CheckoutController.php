<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\OrderRepository;

class CheckoutController extends AbstractController
{
    private $requestStack;
    /**
     * @var Security
     */
    private $security;
    
    public function __construct(RequestStack $requestStack, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->security = $security;
        
    }
    
    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(): Response
    {
        $checkout = $this->getBasket();
        $user = $this->security->getUser();
        return $this->render('checkout/index.html.twig', [
            'controller_name' => 'CheckoutController',
            'basket' => $checkout,
            'user' => $user,
            'readonly' => false
        ]);
    }
    
    private function getBasket(): array
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
        
        return $checkout;
    }
    
    /**
     * @Route("/checkout/details", name="checkout_detais")
     */
    public function checkoutDetails()
    {
        $countries = $this->getCountries();
        $user = $this->security->getUser();
        return $this->render('checkout/details.html.twig', [
            'controller_name' => 'CheckoutController',
            'countries' => $countries,
            'user' => $user
        ]);
    }
    
    /**
     * @Route("/checkout/pay", name="checkout_pay")
     */
    public function checkoutPay(Request $request)
    {
        $countries = $this->getCountries();
        
        $basket = $this->getBasket();
        
        $user = $this->security->getUser();
        
        $session = $this->requestStack->getCurrentRequest()->getSession();
        
        $details["firstName"] = $request->get('firstName');
        $details["lastName"] = $request->get('lastName');
        
        $details["email"] = $request->get('emailAddress');
        
        $details["addressLineOne"] = $request->get('address1');
        $details["addressLineTwo"] = $request->get('address2');
        $details["addressLineThree"] = $request->get('address3');
        $details["addressCity"] = $request->get('addressCity');
        $details["addressCounty"] = $request->get('addressCounty');
        $details["addressPostcode"] = $request->get('addressPostcode');
        $details["addressCountryCode"] = $request->get('addressCountry');
        
        // non-registered client
        if(!$user) {
            $details["notRegistered"] = true;
            $user = $details;
        }
        
        $session->set("checkout_details", $details);
        
        return $this->render('checkout/pay.html.twig', [
            'controller_name' => 'CheckoutController',
            'basket' => $basket,
            'details' => $details,
            'countries' => $countries,
            'user' => $user
        ]);
    }
    
    /**
     * @Route("/checkout/confirm", name="checkout_confirm", methods={"POST"})
     */
    public function checkoutConfirm(Request $request, ProductController $productController, OrderController $orderController)
    {
        $paymentData["status"] = $request->get('data_status');
        $paymentData["id"] = $request->get('data_id');
        
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if($session->has("checkout_details")) {
            $details = $session->get("checkout_details");
        }
        
        $user = $this->security->getUser();
        
        $basket = json_encode($this->getBasket());
        
        $address = json_encode($details);
        
        if ($paymentData["status"] == "COMPLETED")
        {
            
            // non-registered client
            $userId = 0;
            
            if($user) {
                $userId = $user->getId();
            }
                
            $orderController->createOrder($paymentData["id"], $basket, $address, $userId);
            
            
            // Deduct purchased items from stock
            $basket = $this->getBasket();
            if(isset($basket['products'])) {
                foreach( $basket['products'] as $product )
                {
                    $productId = $product['product']->getId();
                    $quantity = $product['quantity'];
                    
                    
                    $productController->delistProduct($productId, $quantity);
                }
            }
            
            
            // Destory basket session
            $session = $this->requestStack->getCurrentRequest()->getSession();
            $basket = $session->set('basket', []);
        }
        
        return $this->render('checkout/confirm.html.twig', [
            'data' => $paymentData,
            'details' => $details
            
        ]);
    }
    
    private function getCountries()
    {
        $countries = json_decode(file_get_contents(__DIR__ . '/../resources/countries.json'));
        return $countries;
    }
}
