<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProfileController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $annonces = $entityManager->getRepository(Annonce::class)->findBy(['user' => $user]);

        return $this->render('profile/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
        ]);
    }
}
