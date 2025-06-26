<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Reponses;
use App\Entity\ReponsesCreneaux;
use App\Form\ReponseTypeForm;
use App\Entity\Invitations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InvitationController extends AbstractController
{
    #[Route('/invitation/{token}', name: 'invitation_show')]
    public function show(string $token, EntityManagerInterface $em, Request $request, MailerInterface $mailer): Response
    {
        $invitation = $em->getRepository(Invitations::class)->findOneBy(['token' => $token]);

        if (!$invitation) {
            throw $this->createNotFoundException('Invitation invalide.');
        }

        $reponse = new Reponses();
        $reponse->setInvitation($invitation);
        $reponse->setDateReponse(new \DateTime());

        $creneaux = $invitation->getReunion()->getCreneaux();

        $form = $this->createForm(ReponseTypeForm::class, $reponse, [
            'creneaux' => $creneaux
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($reponse);

            $selectedCreneaux = $form->get('reponsesCreneauxes')->getData();

            foreach ($selectedCreneaux as $creneau) {
                $reponseCreneaux = new ReponsesCreneaux();
                $reponseCreneaux->setReponse($reponse);
                $reponseCreneaux->setCreneaux($creneau);
                $reponseCreneaux->setConfirmer(true);
                $em->persist($reponseCreneaux);
            }

            $em->flush();


            $organisateur = $invitation->getReunion()->getUser();
            if ($organisateur && $organisateur->getEmail()) {

                $listeCreneaux = array_map(function ($creneau) {
                    return $creneau->getStartTime()->format('d/m/Y') .
                        ' de ' . $creneau->getStartTime()->format('H:i') .
                        ' à ' . $creneau->getEndTime()->format('H:i');
                }, iterator_to_array($selectedCreneaux));

                $email = (new TemplatedEmail())
                    ->from('from@example.com')
                    ->to($organisateur->getEmail())
                    ->subject('Nouvelle réponse à votre réunion')
                    ->htmlTemplate('notifications/notification.html.twig')
                    ->context([
                        'prenom' => $reponse->getPrenom(),
                        'nom' => $reponse->getNom(),
                        'titreReunion' => $invitation->getReunion()->getTitre(),
                        'commentaire' => $reponse->getCommentaires(),
                        'valider' => $reponse->isValider(),
                        'creneaux' => $listeCreneaux,
                        'prenom_organisateur' => $organisateur->getPrenom(),
                        'nom_organisateur' => $organisateur->getNom(),

                        'lienTableauBord' => $this->generateUrl('app_tableau_bord', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    ]);

                $mailer->send($email);
            }

            $this->addFlash('success', 'Votre réponse a été enregistrée.');
            return $this->redirectToRoute('invitation_show', ['token' => $token]);
        }

        return $this->render('invitation/index.html.twig', [
            'invitation' => $invitation,
            'reunion' => $invitation->getReunion(),
            'form' => $form->createView(),
            'organisateur' => $invitation->getReunion()->getUser(),
        ]);
    }
}
