<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BooksController extends AbstractController
{
    /**
     * @Route("/search-books", name="app_search", methods={"POST"})
     */
    public function index(Request $request)
    {

        $search = $request->request->get('userSearch');

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes?q=' . $search);

        $content = $response->toArray()['items'];
        
        return $this->render('books/index.html.twig', [
            'books' => $content
        ]);
    }
}
