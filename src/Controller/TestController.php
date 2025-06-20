<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class TestController extends AbstractController
{
    #[Route('/test-email', name: 'app_test_email')]
public function testEmail(MailerInterface $mailer): Response
{
    $email = (new Email())
        ->from('from@example.com')
        ->to('to@example.com') 
        ->subject('Test Email')
        ->text('Ceci est un test');

    $mailer->send($email);

    return new Response('Email envoyé avec succès');
}

}




