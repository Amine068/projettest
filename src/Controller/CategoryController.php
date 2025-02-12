<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\Subcategory;
use App\Form\SubcategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/category/add', name: 'add_category')]
    #[Route('/category/edit/{id}', name: 'edit_category')]
    public function add(Category $category = null, EntityManagerInterface $entityManager, Request $request): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        if ($category == null) {
            $category = new Category();
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/addedit.html.twig', [
            'form' => $form,
            'categories' => $categories
        ]);
    }

    #[Route('/category/{category}/add-subcategory', name: 'add_subcategory')]
    #[Route('/category/{category}/edit-subcategory/{subCategory}', name: 'edit_subcategory')]
    public function addSubCategoryToCategory(Category $category, Subcategory $subCategory = null,EntityManagerInterface $entityManager, Request $request): Response
    {
        // for header
        $categories = $entityManager->getRepository(Category::class)->findAll();


        if ($subCategory == null) {
            $subCategory = new Subcategory();
        }

        $form = $this->createForm(SubcategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subCategory = $form->getData();

            $category->addSubcategory($subCategory);

            $entityManager->persist($category);
            $entityManager->persist($subCategory);
            $entityManager->flush();

            return $this->redirectToRoute('show_category', ['id' => $category->getId()]);
        }

        return $this->render('category/addeditsub.html.twig', [
            'form' => $form,
            'categories' => $categories
        ]);
    }

    #[Route('/delete-subcategory/{id}', name: 'delete_subcategory')]
    public function deleteSubCategory(Subcategory $subcategory, EntityManagerInterface $entityManager): Response
    {   
        
        $id = $subcategory->getCategory()->getId();

        $entityManager->remove($subcategory);
        $entityManager->flush();

        return $this->redirectToRoute('show_category', ['id' => $id]);
    }

    #[Route('/category/{id}', name: 'show_category')]
    public function show(Category $category, EntityManagerInterface $entityManager): Response
    {

        $categories = $entityManager->getRepository(Category::class)->findAll();


        return $this->render('category/show.html.twig', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    #[Route('/category/delete/{id}', name: 'delete_category')]
    public function delete(Category $category, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_category');
    }
}
