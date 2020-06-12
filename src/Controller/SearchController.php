<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SearchController extends AbstractController
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
        
        return $this->render('search/index.html.twig', [
            'books' => $content
        ]);
    }

    /**
     * @Route("/add-book", name="app_add_book", methods={"POST"})
     */
    public function addBook(Request $request)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $bookId = $request->request->get('bookId');

        $repository = $this->getDoctrine()->getRepository(Book::class);
        $bookIsInDB = $repository->findBy(['bookId' => $bookId]);

        $em = $this->getDoctrine()->getManager();

        if (empty($bookIsInDB)) {
            $client = HttpClient::create();
            $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes/' . $bookId);
    
            $content = $response->toArray();
    
            $book = new Book;
            $book->setBookId($content['id']);
            $book->setTitle($content['volumeInfo']['title']);
            $book->setDescription($content['volumeInfo']['description']);
            $book->setPublishedDate(null);
            $book->setPublisher($content['volumeInfo']['publisher']);
            $book->setPageCount($content['volumeInfo']['pageCount']);
            $book->setThumbnail($content['volumeInfo']['imageLinks']['thumbnail']);
            $book->addUser($user);
            $em->persist($book);
        } else {
            $users = $bookIsInDB[0]->getUsers();

            $addUser = true;
    
            foreach ($users as $owner) {
                if ($owner === $user) {
                    $bookIsInDB[0]->removeUser($user);
                    $addUser = false;
                }
            }
    
            if ($addUser) {
                $bookIsInDB[0]->addUser($user);
            }
    
            $em->persist($bookIsInDB[0]);
        }
        
        $em->flush();
                
        return $this->redirectToRoute('app_account');
    }
}
