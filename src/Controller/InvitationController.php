<?php

namespace App\Controller;

use App\Entity\Invitations;
use App\Form\InvitationTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvitationController extends AbstractController
{
    #[Route('/invitation/{token}', name: 'invitation_show')]
    public function show(string $token, EntityManagerInterface $entityManager, Request $request): Response
    {
        $invitation = $entityManager->getRepository(Invitations::class)->findOneBy(['token' => $token]);

        if (!$invitation) {
            throw $this->createNotFoundException('Invitation invalide.');
        }

        $form = $this->createForm(InvitationTypeForm::class, $invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre réponse a été enregistrée.');
            return $this->redirectToRoute('invitation_show', ['token' => $token]);
        }

        return $this->render('invitation/index.html.twig', [
            'invitation' => $invitation,
            'reunion' => $invitation->getReunion(),
            'form' => $form->createView(),
        ]);
    }
}
