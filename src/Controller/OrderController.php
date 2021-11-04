<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;
use PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

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
    
    /**
     * 
     * @param string $to
     * @param string $clientName
     * @param string $subject
     * @param string $bodyHTML
     * @param string $bodyPlain
     * @throws \Exception
     * @return boolean
     */
    private function sendEmail(string $to, string $clientName, string $subject, string $bodyHTML, string $bodyPlain)
    {
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host       = 'smtp.example.com'; //Set the SMTP server to send through
            $mail->SMTPAuth   = true; //Enable SMTP authentication
            $mail->Username   = 'user@example.com'; //SMTP username
            $mail->Password   = 'secret'; //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port       = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress($to, $clientName); //Add a recipient
            //$mail->addAddress('ellen@example.com');  //Name is optional
            $mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name
            
            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $bodyHTML;
            $mail->AltBody = $bodyPlain;
            
            $mail->send();
            
            return true;
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            throw $e;
        }
    }
}
