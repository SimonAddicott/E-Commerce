<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    
    /**
     * @Route("/register", name="app_register", methods={"GET"})
     */
    public function register(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        $countries = json_decode(file_get_contents(__DIR__ . '/../resources/countries.json'));
        
        return $this->render('security/register.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'countries' => $countries]);
    }
    
    /**
     * @Route("/register", name="create_user", methods={"POST"})
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $email = $request->request->get('email');
        $plainPassword = $request->request->get('password');
        
        $firstName = $request->request->get('firstName');
        $lastName = $request->request->get('lastName');
        
        $addressLineOne = $request->request->get('address1');
        $addressLineTwo = $request->request->get('address2');
        $addressLineThree = $request->request->get('address3');
        $addressLineCity = $request->request->get('addressCity');
        $addressLineCounty = $request->request->get('addressCounty');
        $addressLinePostcode = $request->request->get('addressPostcode');
        $addressLineCountryCode = $request->request->get('addressCountry');
        
        
        $entityManager = $this->getDoctrine()->getManager();
        
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(["ROLE_USER"]);
        
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        
        $user->setAddressOne($addressLineOne);
        $user->setAddressTwo($addressLineTwo);
        $user->setAddressThree($addressLineThree);
        $user->setAddressCity($addressLineCity);
        $user->setAddressCounty($addressLineCounty);
        $user->setAddressPostcode($addressLinePostcode);
        $user->setAddressCountryCode($addressLineCountryCode);
        
        $passwordHash = $passwordEncoder->encodePassword($user, $plainPassword);
        
        $user->setPassword($passwordHash);
        $error = "Please login to continue";
        $userId = '';
        
        try{
        
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);
        
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();  
        
        $userId = $user->getId();
        
        } catch(\Exception $e) {
            $error = "It appears we already have your email of " . $email . " on our records.";
        }
        // get the login error if there is one
        //$userId = $this->forward('App\Security\LoginFormAuthentictorAuthenticator:createUser', $userData);
        // last username entered by the user
        
        
        return $this->render('security/login.html.twig', ['last_username' => $userId, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
