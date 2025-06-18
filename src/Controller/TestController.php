<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final class TestController extends AbstractController
{
    #[Route('/create-test-user', name: 'create_test_user')]
    public function createTestUser(
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): Response {
        $user = new Users();
        $user->setEmail('test@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setNom('Test');
        $user->setPrenom('Utilisateur');
        $user->setPassword(
            $hasher->hashPassword($user, '123456')
        );

        $em->persist($user);
        $em->flush();

        return new Response('✅ Utilisateur "test@example.com" avec mot de passe "123456" créé.');
    }
}
