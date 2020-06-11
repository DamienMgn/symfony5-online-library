<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="app_account")
     */
    public function index()
    {
        $user = $this->getUser();

        $books = $user->getBooks();

        return $this->render('account/index.html.twig', [
            'books' => $books,
        ]);
    }
}
