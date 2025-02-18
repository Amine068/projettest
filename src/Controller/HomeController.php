<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $annonces = $entityManager->getRepository(Annonce::class)->findAll();

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces
        ]);
    }

    #[Route('/search', name: 'search_annonces')]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        $recherche = $request->query->get('recherche');
        $annonces = $entityManager->getRepository(Annonce::class)->search($recherche);

        return $this->render('home/index.html.twig', [
            'annonces' => $annonces,
            'categories' => $categories
        ]);
    }
}
