<?php



namespace App\Controller;

use App\Entity\Creneaux;
use App\Entity\Invitations;
use App\Service\TokenGenerator;
use App\Entity\Reunions;
use App\Form\ReunionConfirmType;
use App\Form\ReunionConfirmTypeForm;
use App\Form\ReunionTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReunionController extends AbstractController
{

    #[Route('/reunion/new', name: 'reunion_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, TokenGenerator $tokenGenerator, MailerInterface $mailer): Response
    {
        $reunion = new Reunions();
        $form = $this->createForm(ReunionTypeForm::class, $reunion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reunion->setUser($this->getUser());
            $reunion->setDateCreation(new \DateTime());


            $entityManager->persist($reunion);
            $entityManager->flush();


            $inviteEmails = $form->get('inviteEmails')->getData();

            foreach ($inviteEmails as $inviteEmail) {
                $invitation = new Invitations();
                $invitation->setReunion($reunion);
                $invitation->setInviteEmail($inviteEmail);
                $invitation->setToken($tokenGenerator->generate());

                $entityManager->persist($invitation);

                $url = $this->generateUrl(
                    'invitation_show',
                    ['token' => $invitation->getToken()],
                    \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL
                );



                $email = (new Email())
                    ->from('from@example.com')
                    ->to($inviteEmail)
                    ->subject('Invitation à la réunion')
                    ->html("<p>Vous êtes invité à la réunion <strong>{$reunion->getTitre()}</strong><br>
                    <p>Lieu : {$reunion->getLieu()}<br>
                    Description : {$reunion->getDescription()}</p>
                    <p>Organisée par : {$reunion->getUser()->getEmail()}</p>
                    <p>Merci de répondre via ce lien : <a href='{$url}'>Cliquer ici pour voir les détails</a></p>");
                $mailer->send($email);
            }


            $entityManager->flush();

            $this->addFlash('success', 'Réunion et invitations créées avec succès !');

            return $this->redirectToRoute('app_tableau_bord');
        }

        return $this->render('reunion/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/reunion/edit/{id}', name: 'reunion_edit')]
    public function edit(Reunions $reunion, Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ReunionTypeForm::class, $reunion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();


            foreach ($reunion->getInvitations() as $invitation) {
                $url = $this->generateUrl('invitation_show', ['token' => $invitation->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
                $email = (new Email())
                    ->from('from@example.com')
                    ->to($invitation->getInviteEmail())
                    ->subject('Mise à jour de la réunion')
                    ->html("<p>La réunion <strong>{$reunion->getTitre()}</strong> a été modifiée.</p>
                <p>Veuillez vérifier les détails ici : <a href='{$url}'>Voir la réunion</a></p>");
                $mailer->send($email);
            }

            $this->addFlash('success', 'Réunion modifiée avec succès et les invités ont été notifiés.');
            return $this->redirectToRoute('app_tableau_bord');
        }

        return $this->render('reunion/edit.html.twig', [
            'form' => $form->createView(),
            'reunion' => $reunion,
        ]);
    }
    #[Route('/reunion/cancel/{id}', name: 'reunion_cancel')]
    public function cancel(Reunions $reunion, EntityManagerInterface $em, MailerInterface $mailer): Response

    {
        $reunion->setStatus(\App\Enum\ReunionStatus::ANNULEE);
        foreach ($reunion->getInvitations() as $invitation) {
            $url = $this->generateUrl('invitation_show', [
                'token' => $invitation->getToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new Email())
                ->from('from@example.com')
                ->to($invitation->getInviteEmail())
                ->subject('Réunion annulée')
                ->html("<p>La réunion <strong>{$reunion->getTitre()}</strong> est maintenant annulée. 
                Désolé pour le désagrément, nous allons reprogrammer une autre réunion prochainement,
                 À bientôt.</p>");



            $mailer->send($email);
        }
        $em->flush();

        $this->addFlash('success', 'Réunion annulée avec succès.');

        return $this->redirectToRoute('app_tableau_bord');
    }
    #[Route('/reunion/confirm/{id}', name: 'reunion_confirm')]
    public function confirm(Reunions $reunion, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $reunion->setStatus(\App\Enum\ReunionStatus::CONFIRMEE);

        foreach ($reunion->getInvitations() as $invitation) {
            $url = $this->generateUrl('invitation_show', [
                'token' => $invitation->getToken()
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new Email())
                ->from('from@example.com')
                ->to($invitation->getInviteEmail())
                ->subject('Réunion confirmée')
                ->html("<p>La réunion <strong>{$reunion->getTitre()}</strong> est maintenant confirmée.</p>
                    <p>Lieu : {$reunion->getLieu()}</p>
                    <p>Description : {$reunion->getDescription()}</p>
                    <p>Date et heure : {$reunion->getCreneaux()->first()->getStartTime()->format('d/m/Y H:i')} - 
                    {$reunion->getCreneaux()->first()->getEndTime()->format('H:i')}</p>
                    <p>Consultez les détails ici : <a href='{$url}'>Voir la réunion</a></p>");

            $mailer->send($email);
        }

        $em->flush();

        $this->addFlash('success', 'Réunion confirmée et les invités ont été notifiés.');
        return $this->redirectToRoute('app_tableau_bord');
    }
    #[Route('/reunion/choix-creneau/{id}', name: 'reunion_choix_creneau')]
    public function choixCreneau(Reunions $reunion, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReunionConfirmTypeForm::class, null, [
            'creneaux' => $reunion->getCreneaux(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedCreneau = $form->get('creneau')->getData();

            foreach ($reunion->getCreneaux() as $creneau) {
                if ($creneau !== $selectedCreneau) {
                    $em->remove($creneau);
                }
            }

            $em->flush();

            $this->addFlash('success', 'Le créneau final a été choisi avec succès.');

            return $this->redirectToRoute('app_tableau_bord');
        }

        return $this->render('reunion/choix_final.html.twig', [
            'form' => $form->createView(),
            'reunion' => $reunion,
        ]);
    }
}
