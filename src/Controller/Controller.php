<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class Controller {

    public function home() {
        return new Response("hello, Simon");
    }
    
    public function getProducts() {
        // getproducts from repository
        return new Response('[{"id":"01"}, {"id":"02"}]');
    }
    
    public function getProduct($productId) {
        // getproducts from repository
        return new Response('[{"id":"' . $productId . '"}]');
    }
    
    public function createProduct() {
        // getproducts from repository
        return new Response('[{"id":"new"}]');
    }
}
