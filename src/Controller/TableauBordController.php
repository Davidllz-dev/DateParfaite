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
    #[Route('/tableau-bord', name: 'app_tableau_bord')]
public function index(EntityManagerInterface $em): Response
{
    $user = $this->getUser();

    $reunions = $em->getRepository(Reunions::class)
        ->createQueryBuilder('r')
        ->leftJoin('r.invitations', 'i')
        ->leftJoin('i.reponses', 'resp')
        ->addSelect('i', 'resp')
        ->where('r.user = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();

    return $this->render('tableau_bord/index.html.twig', [
        'user' => $user,
        'reunions' => $reunions,
    ]);
}



      
}
