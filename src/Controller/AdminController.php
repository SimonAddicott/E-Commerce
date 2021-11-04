<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(ProductController $productController): Response
    {
        $products = $productController->getProducts();
        
        
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'products' => $products
        ]);
    }
    
    /**
     * @Route("/admin/orders", name="admin_orders")
     */
    public function showOrders(OrderController $orderController): Response
    {
        $orders = $orderController->getOrders();
        
        
        return $this->render('admin/orders.html.twig', [
            'orders' => $orders
        ]);
    }
    
    /**
     * @Route("/admin/orders/{orderId}", name="admin_order")
     */
    public function showOrder(OrderController $orderController, int $orderId): Response
    {
        $order = $orderController->getOrder($orderId);
        $user = $this->getUserDetails($order->getUserId());
        
        
        return $this->render('admin/order.html.twig', [
            'order' => $order,
            'client' => $user
        ]);
    }
    
    private function getUserDetails(int $userId)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(User::class);
        $user = $repository->find($userId);
        return $user;
    }
}
