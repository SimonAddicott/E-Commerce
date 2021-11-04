<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Configuration;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityNotFoundException;

class ConfigurationController extends AbstractController
{
    private $requestStack;
    
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    private function loadConfig()
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $repository = $this->getDoctrine()->getManager()->getRepository(Configuration::class);
        $config = $repository->findAll();
        if(!$config)
        {
            $configSession = [];
            foreach($config as $configItem)
            {
                $configSession[$configItem->getName()] = $configItem->getValue();
            }
            $session->set('config', $configSession);
        }
    }
    
    public function requestConfigByName(string $name)
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        
        if (!$session->get('config'))
        {
            $this->loadConfig();
        }
        
        if(isset($session[$name]))
        {
            return $session[$name];
        }
        
        throw new EntityNotFoundException("No configuration found for '$name'");
    }
}
