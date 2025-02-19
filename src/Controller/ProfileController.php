<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Annonce;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
        $avatarExists = file_exists($this->getParameter('avatar_directory') . '/' . $user->getAvatar());

        return $this->render('profile/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
            'avatarExists' => $avatarExists
        ]);
    }

    #[Route('/account/edit', name: 'edit_account')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categories = $entityManager->getRepository(Category::class)->findAll();

        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get('avatar')->getData();
            if ($avatar) {
                if ($user->getAvatar() && file_exists($this->getParameter('avatar_directory').'/'.$user->getAvatar())) {
                    unlink($this->getParameter('avatar_directory').'/'.$user->getAvatar());
                }
                $newAvatar = uniqid() . '.' . $avatar->guessExtension();
                $avatar->move($this->getParameter('avatar_directory'), $newAvatar);
                $user->setAvatar($newAvatar);
            }
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_account');
        }
        return $this->render('profile/edit.html.twig', [
            'categories' => $categories,
            'form' => $form
        ]);
    }

    #[Route('/account/delete', name:'delete_account')]
    public function deleteAccount(EntityManagerInterface $entityManager): Response
    {   
        $user = $this->getUser();


        return $this->redirectToRoute('app_logout');
    }

    public function anonymizeUser(User $user)
    {
        $newUsername = hash('sha256', uniqid());
        $newEmail = hash('sha256', uniqid());
        $user->setUsername($newUsername);
        $user->setEmail($newEmail);
        $user->setPassword(NULL);
        $user->setRoles(['ROLE_DELETE']);
        $user->setGoggleId(NULL);
    }

    #[Route('/account/favorites', name: 'app_favorites')]
    public function show_favorites(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('profile/favorites.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/account/favorites/add/{id}', name: 'add_favorite')]
    public function add_favorite(Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $user->addFavoriteAnnonce($annonce);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_favorites');
    }

    #[Route('/account/favorites/remove/{id}', name: 'remove_favorite')]
    public function remove_favorite(Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $user->removeFavoriteAnnonce($annonce);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_favorites');
    }
}
