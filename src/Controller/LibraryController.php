<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/library")
 */
class LibraryController extends AbstractController
{
    /**
     * @Route("/", name="app_library")
     */
    public function index()
    {
        $user = $this->getUser();

        $books = $user->getBooks();

        return $this->render('library/user-books.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/categories", name="app_categories")
     */
    public function showCategories()
    {
        $user = $this->getUser();

        $categories = $user->getCategories();

        return $this->render('library/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/add-category", name="app_add_category", methods={"POST"})
     */
    public function addCategory(Request $request)
    {
        $user = $this->getUser();

        $category = new Category;

        $em = $this->getDoctrine()->getManager();

        $category->setName($request->request->get('category'));
        $category->setUser($user);

        $em->persist($category);
        $em->flush();

        return $this->redirectToRoute('app_categories');
    }

    /**
     * @Route("/delete-category/{category}", name="app_delete_category", methods={"POST"})
     */
    public function deleteCategory(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        
        return $this->redirectToRoute('app_categories');
    }
}
