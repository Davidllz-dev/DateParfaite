<?php



namespace App\Controller;

use App\Entity\Invitations;
use App\Service\TokenGenerator;
use App\Entity\Reunions;
use App\Form\ReunionTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

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
                    ->from('noreply@tondomaine.com')
                    ->to($inviteEmail)
                    ->subject('Invitation à la réunion')
                    ->html("<p>Vous êtes invité à la réunion <strong>{$reunion->getTitre()}</strong>.</p>
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
}
