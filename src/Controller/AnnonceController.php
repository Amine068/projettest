<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Form\AnnonceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AnnonceController extends AbstractController
{
    #[Route('/annonce', name: 'app_annonce')]
    public function index(): Response
    {
        return $this->render('annonce/index.html.twig', [
            'controller_name' => 'AnnonceController',
        ]);
    }

    #[Route('/annonce/new', name: 'add_new_annonce')]
    // #[Route('/annonce/{id}/edit', name: 'edit_annonce')]
    public function newedit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $categories = $entityManager->getRepository(Category::class)->findAll();

        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce = $form->getData();
            $annonce->setDateOfPost(new \DateTime());
            $annonce->setUser($this->getUser());
            $annonce->setIsValidated(false);
            $annonce->setIsLocked(false);

            $entityManager->persist($annonce);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_home');
        }
    
        return $this->render('annonce/newedit.html.twig', [
            'form' => $form,
            'categories' => $categories
        ]);
    }

    #[Route('/get-subcategories/{categoryId}', name: 'get_subcategories', methods: ['GET'])]
    public function getSubcategories($categoryId, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $entityManager->getRepository(Category::class)->find($categoryId);

        if (!$category) {
            return new JsonResponse(['subcategories' => []]);
        }

        $subcategories = $category->getSubcategory();
        $data = [];

        foreach ($subcategories as $subcategory) {
            $data[] = [
                'id' => $subcategory->getId(),
                'name' => $subcategory->getName(),
            ];
        }

        return new JsonResponse(['subcategories' => $data]);
    }

    #[Route('/annonce/{id}/delete', name: 'delete_annonce')]
    public function delete(Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute('app_annonce');
    }

    #[Route('/annonce/{id}', name: 'show_annonce')]
    public function show(Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();


        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'categories' => $categories
        ]);
    }
}
