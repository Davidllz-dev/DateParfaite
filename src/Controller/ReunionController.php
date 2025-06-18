<?php



namespace App\Controller;

use App\Entity\Reunions;
use App\Form\ReunionType;
use App\Form\ReunionTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReunionController extends AbstractController
{
    #[Route('/reunion/new', name: 'reunion_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reunion = new Reunions();
        $form = $this->createForm(ReunionTypeForm::class, $reunion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Attribution automatique du user connecté
            $reunion->setUser($this->getUser());

            // Date de création
            $reunion->setDateCreation(new \DateTime());

            // Doctrine va aussi persister les créneaux liés grâce à cascade: ['persist']
            $entityManager->persist($reunion);
            $entityManager->flush();

            $this->addFlash('success', 'Réunion créée avec succès !');
            return $this->redirectToRoute('tableau_bord');
        }

        return $this->render('reunion/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
