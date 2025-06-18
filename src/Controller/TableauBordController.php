<?php

namespace App\Controller;

use App\Entity\Reunions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TableauBordController extends AbstractController
{
    #[Route('/tableaubord', name: 'app_tableau_bord')]
public function index(EntityManagerInterface $em): Response
{
    $user = $this->getUser();

    $reunions = $em->getRepository(Reunions::class)->findBy(['user' => $user]);

    return $this->render('tableau_bord/index.html.twig', [
        'user' => $user,
        'reunions' => $reunions,
    ]);

}


      
}
