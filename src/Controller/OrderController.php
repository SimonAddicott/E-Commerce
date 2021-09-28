<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;

class OrderController extends AbstractController
{
    
    public function getOrders()
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository(Order::class);
        
        $orders = $repository->findAll();

        return $orders;
    }
    
    public function getOrder($orderId)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository(Order::class);
        
        $order = $repository->find($orderId);
        return $order;
    }
    
    public function createOrder($paymentRef, $basket, $address, $userId, $paymentDetails = [])
    {
        $manager = $this->getDoctrine()->getManager();
        
        $order = new Order(); // Great band
        $order->setAddress($address);
        $order->setBasket($basket);
        $order->setDatetime(new \DateTime('NOW'));
        $order->setPaymentDetails('');
        $order->setPaymentRef($paymentRef);
        $order->setUserId($userId);
        
        $manager->persist($order);
        
        $manager->flush();
        
        return $order->getId();
    }
}
