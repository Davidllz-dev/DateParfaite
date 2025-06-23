<?php

// src/Controller/InvitationController.php

namespace App\Controller;

use App\Entity\Invitations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvitationController extends AbstractController
{
    #[Route('/invitation/{token}', name: 'invitation_show')]
    public function show(string $token, EntityManagerInterface $entityManager): Response
    {
        $invitation = $entityManager->getRepository(Invitations::class)->findOneBy(['token' => $token]);

        if (!$invitation) {
            throw $this->createNotFoundException('Invitation invalide.');
        }

        $reunion = $invitation->getReunion();

        return $this->render('invitation/index.html.twig', [
            'invitation' => $invitation,
            'reunion' => $reunion,
        ]);
    }
}


