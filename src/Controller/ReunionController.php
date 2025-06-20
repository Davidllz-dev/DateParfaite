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
            
            $reunion->setUser($this->getUser());

        
            $reunion->setDateCreation(new \DateTime());

            
            $entityManager->persist($reunion);
            $entityManager->flush();

            $this->addFlash('success', 'Réunion créée avec succès !');
            return $this->redirectToRoute('app_tableau_bord');

        }

        return $this->render('reunion/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
