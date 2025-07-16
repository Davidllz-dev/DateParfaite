<?php

namespace App\Controller;

use App\Entity\Reunions;
use App\Entity\Invitations;
use App\Form\ReunionTypeForm;
use App\Form\ReunionConfirmTypeForm;
use App\Service\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            $reunion->setDateCreation(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

            $entityManager->persist($reunion);

            foreach ($form->get('inviteEmails')->getData() as $inviteEmail) {
                $invitation = new Invitations();
                $invitation->setReunion($reunion);
                $invitation->setInviteEmail($inviteEmail);
                $invitation->setToken($tokenGenerator->generate());

                $entityManager->persist($invitation);

                $url = $this->generateUrl('invitation_show', ['token' => $invitation->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new Email())
                    ->from('from@example.com')
                    ->to($inviteEmail)
                    ->subject('Invitation à la réunion')
                    ->html("
                        <p>Vous êtes invité à la réunion <strong>{$reunion->getTitre()}</strong></p>
                        <p>Lieu : {$reunion->getLieu()}<br>
                        Description : {$reunion->getDescription()}</p>
                        <p>Organisée par : {$reunion->getUser()->getEmail()}</p>
                        <p>Merci de répondre via ce lien : <a href='{$url}'>Voir les détails</a></p>
                    ");
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
                    ->html("
                        <p>La réunion <strong>{$reunion->getTitre()}</strong> a été modifiée.</p>
                        <p><a href='{$url}'>Voir la réunion</a></p>
                    ");
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
            $email = (new Email())
                ->from('from@example.com')
                ->to($invitation->getInviteEmail())
                ->subject('Réunion annulée')
                ->html("
                    <p>La réunion <strong>{$reunion->getTitre()}</strong> est maintenant annulée.</p>
                    <p>Désolé pour le désagrément. Une autre réunion sera reprogrammée prochainement.</p>
                ");
            $mailer->send($email);
        }

        $em->flush();
        $this->addFlash('success', 'Réunion annulée avec succès.');
        return $this->redirectToRoute('app_tableau_bord');
    }

    #[Route('/reunion/confirm/{id}', name: 'reunion_confirm')]
    public function confirm(Reunions $reunion, Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $timezone = new \DateTimeZone('Europe/Paris');
        $now = new \DateTime('now', $timezone);

        $reunion->setStatus(\App\Enum\ReunionStatus::CONFIRMEE);
        $reunion->setDateCreation($now);

        $creneau = $reunion->getCreneaux()->first();
        $start = $creneau->getStartTime();
        $end = $creneau->getEndTime();

        $format = fn($dt) => $dt->format('Ymd\THis');

        $icsContent = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//YourCompany//YourApp//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
BEGIN:VTIMEZONE
TZID:Europe/Paris
BEGIN:STANDARD
DTSTART:20241027T030000
TZOFFSETFROM:+0200
TZOFFSETTO:+0100
TZNAME:CET
END:STANDARD
BEGIN:DAYLIGHT
DTSTART:20250330T020000
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
END:DAYLIGHT
END:VTIMEZONE
BEGIN:VEVENT
UID:reunion-{$reunion->getId()}@yourdomain.com
SUMMARY:{$reunion->getTitre()}
DESCRIPTION:{$reunion->getDescription()}
LOCATION:{$reunion->getLieu()}
DTSTART;TZID=Europe/Paris:{$format($start)}
DTEND;TZID=Europe/Paris:{$format($end)}
DTSTAMP;TZID=Europe/Paris:{$format($now)}
END:VEVENT
END:VCALENDAR
ICS;

        $icsFilename = "reunion_{$reunion->getId()}.ics";
        $icsPath = $this->getParameter('kernel.project_dir') . "/public/ics/$icsFilename";
        file_put_contents($icsPath, $icsContent);

        $icsUrl = $request->getSchemeAndHttpHost() . "/ics/$icsFilename";
        $htmlContent = fn() => "
            <p>La réunion <strong>{$reunion->getTitre()}</strong> est maintenant confirmée.</p>
            <p>Lieu : {$reunion->getLieu()}</p>
            <p>Description : {$reunion->getDescription()}</p>
            <p>Date et heure : {$start->format('d/m/Y H:i')} - {$end->format('H:i')}</p>
            <p><a href='$icsUrl'>Ajouter à votre calendrier</a></p>
        ";

        foreach ($reunion->getInvitations() as $invitation) {
            $mailer->send((new Email())
                ->from('from@example.com')
                ->to($invitation->getInviteEmail())
                ->subject('Réunion confirmée')
                ->html($htmlContent())
            );
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

    #[Route('/reunion/archive/{id}', name: 'reunion_archive')]
    public function archive(Reunions $reunion, EntityManagerInterface $em): Response
    {
        $reunion->setArchived(true);
        $em->flush();

        $this->addFlash('info', 'La réunion a été retirée du tableau de bord.');
        return $this->redirectToRoute('app_tableau_bord');
    }
}
