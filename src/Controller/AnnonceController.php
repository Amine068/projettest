<?php

namespace App\Controller;

use App\Entity\Image;
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
    #[Route('/annonce/{id}/edit', name: 'edit_annonce')]
    public function newedit(Annonce $annonce = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // vérification de si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // récupération des catégories pour l'affichage dans le header (rien a voir avec l'ajout d'une annonce)
        $categories = $entityManager->getRepository(Category::class)->findAll();

        // création d'une nouvelle entité Annonce
        if (!$annonce) {
            $annonce = new Annonce();
        }
        // $annonce = new Annonce();

        //création d'un formulaire a partir de annonceType et de l'entité annonce
        $form = $this->createForm(AnnonceType::class, $annonce);
        // traitement des données du formulaire
        $form->handleRequest($request);



        // on verifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();
            if ($images) {
                if ($annonce->getImages()) {
                    foreach ($annonce->getImages() as $image) {
                        unlink($this->getParameter('annonce_image_directory') . '/' . $image->getPath());
                        $entityManager->remove($image);
                    }
                }
                foreach ($images as $imageFile) {
                    if ($imageFile->getSize() > 1000000) {
                        $this->addFlash('error', 'L\'image ne doit pas dépasser 1Mo');
                        return $this->redirectToRoute('add_new_annonce');
                    }
                    if (!in_array($imageFile->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                        $this->addFlash('error', 'L\'image doit être au format jpeg ou png');
                        return $this->redirectToRoute('add_new_annonce');
                    }
                    $image = new Image();
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                    $imageFile->move($this->getParameter('annonce_image_directory'), $newFilename);
                    $image->setPath($newFilename);
                    $annonce->addImage($image);
                    $entityManager->persist($image);
                }
            }
            // $cityval = $_POST['annonce'];
            // dump($cityval);
            // $city = filter_input_array(INPUT_POST, $_POST['annonce']);
            // dd($city);

            // $annonce->setCity($city);
            // on récupère les données du formulaire et on les met dans l'entité annonce
            $annonce = $form->getData();
            // $annonce->setCity($form->get('city')->getData());
            // ajout de la date du jour du post a l'annonce
            $annonce->setDateOfPost(new \DateTime());

            // ajout de l'utilisateur connecté a l'annonce
            $annonce->setUser($this->getUser());

            // on met l'annonce en non validé (à valider par un modérateur avant d'être visible)
            $annonce->setIsValidated(false);

            // on rend l'annonce non vérouillé (permettra d'utiliser un système de signalement)
            $annonce->setIsLocked(false);

            // on persiste l'entité annonce (requete préparé: protection contre les injection SQL)
            $entityManager->persist($annonce);

            // on execute la requete (prepare / execute du PDO en PHP natif)
            $entityManager->flush();
    
            // on redirige l'utilisateur vers la page d'accueil
            return $this->redirectToRoute('app_account');
        }
        
        // on affiche le formulaire
        return $this->render('annonce/newedit.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }

    // #[Route('/getsubcategories/{categoryId}', name: 'get_subcategories', methods: ['GET'])]
    // public function getSubcategories($categoryId, EntityManagerInterface $entityManager): JsonResponse
    // {
    //     // on récupère la catégorie en fonction de l'id
    //     $category = $entityManager->getRepository(Category::class)->find($categoryId);

    //     // vérification de la si la catégorie existe (si l'id est incorrect) et retour d'un tableau vide
    //     if (!$category) {
    //         return new JsonResponse(['subcategories' => []]);
    //     }

    //     // on récupère les sous catégories de la catégorie
    //     $subcategories = $category->getSubcategory();

    //     // on crée un tableau vide
    //     $data = [];

    //     // on ajoute les id et nom de des souscategorie dans le tableau en bouclant sur la collection de sous catégorie de la catégorie
    //     foreach ($subcategories as $subcategory) {
    //         $data[] = [
    //             'id' => $subcategory->getId(),
    //             'name' => $subcategory->getName(),
    //         ];
    //     }

    //     // on retourne le tableau en format JSON
    //     return new JsonResponse(['subcategories' => $data]);
    // }

    #[Route('/annonce/{id}/delete', name: 'delete_annonce')]
    public function delete(Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        foreach ($annonce->getImages() as $image) {
            unlink($this->getParameter('annonce_image_directory') . '/' . $image->getPath());
        }
        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute('app_account');
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
