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
        ->leftJoin('resp.reponsesCreneauxes', 'rc')  
        ->leftJoin('rc.creneaux', 'c')  
        ->addSelect('rc', 'c') 
        ->where('r.user = :user')
        ->andWhere('r.archived = false')
        ->setParameter('user', $user)
        ->orderBy('r.dateCreation', 'DESC')
        ->getQuery()
        ->getResult();

    return $this->render('tableau_bord/index.html.twig', [
        'user' => $user,
        'reunions' => $reunions,
    ]);
}

    

// #[Route('/reunions/archivees', name: 'reunions_archivees')]
// public function archived(EntityManagerInterface $em): Response
// {
//     $user = $this->getUser();

//     $archivedReunions = $em->getRepository(Reunions::class)
//         ->createQueryBuilder('r')
//         ->leftJoin('r.invitations', 'i')
//         ->leftJoin('i.reponses', 'resp')
//         ->addSelect('i', 'resp')
//         ->where('r.user = :user')
//         ->andWhere('r.archived = true') 
//         ->setParameter('user', $user)
//         ->orderBy('r.dateCreation', 'DESC')
//         ->getQuery()
//         ->getResult();

//     return $this->render('tableau_bord/archivees.html.twig', [
//         'reunions' => $archivedReunions,
//     ]);
// }




      
}
