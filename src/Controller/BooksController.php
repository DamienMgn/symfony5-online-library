<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BooksController extends AbstractController
{
    /**
     * @Route("/search-books", name="show_books", methods={"POST"})
     */
    public function index(Request $request)
    {

        $search = $request->request->get('userSearch');


        dump($search); exit;

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes?q=bernard+minier');

        $content = $response->toArray()['items'];
        
        return $this->render('books/index.html.twig', [
            'books' => $content
        ]);
    }
}
