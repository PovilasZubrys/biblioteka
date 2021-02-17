<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Entity\Book;

class BookController extends AbstractController
{
    #[Route('/book', name: 'book_index')]
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $authors = $this->getDoctrine()
        ->getRepository(Author::class)
        ->findAll();

        $books = $this->getDoctrine()
        ->getRepository(Book::class);

        if ("default" == $r->query->get('author_id')) {
            $books = $books->findBy([],['title'=>'asc']);
        } elseif (null !== $r->query->get('author_id'))  {
            $books = $books->findBy(['author_id' => $r->query->get('author_id')]);
        } else {
            $books = $books->findBy([],['title'=>'asc']);
        }

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'authors' => $authors,
            'success' => $r->getSession()->getFlashBag()->get('success', []),
            'authorId' => $r->query->get('author_id') ?? 'default',
        ]);
    }

    #[Route('/book/create', name: 'book_create', methods: ['GET'])]
    public function create(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('create_author', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Blogas Token CSRF');
            return $this->redirectToRoute('author_create');
        }

        $authors = $this->getDoctrine()
        ->getRepository(Author::class)
        ->findAll();
        
        return $this->render('book/create.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'authors' => $authors,
        ]);
    }

    #[Route('/book/store', name: 'book_store', methods: ['POST'])]
    public function store(Request $r, ValidatorInterface $validator): Response
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $author = $this->getDoctrine()
        ->getRepository(Author::class)
        ->find($r->request->get('book_author_id'));

        if(null === $author) {
            $r->getSession()->getFlashBag()->add('errors', 'Pasirink autoriu');
        }

        $book = new Book;
        $book
        ->setTitle($r->request->get('book_title'))
        ->setIsbn($r->request->get('book_isbn'))
        ->setPages((int)$r->request->get('book_pages'))
        ->setShortDescription($r->request->get('book_short_description'))
        ->setAuthor($author);

        $errors = $validator->validate($book);

        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());

            }

            return $this->redirectToRoute('book_create');
        }

        if(null === $author) {
            return $this->redirectToRoute('book_create');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('book_index');
    }

    #[Route('/book/edit/{id}', name: 'book_edit', methods: ['GET'])]
    public function edit(int $id, Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');



        $book = $this->getDoctrine()
        ->getRepository(Book::class)
        ->find($id);

        $authors = $this->getDoctrine()
        ->getRepository(Author::class)
        ->findAll();
        
        $r->getSession()->getFlashBag()->add('success', 'Knyga buvo sekmingai paredaguota.');
            
        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'authors' => $authors
        ]);
    }

    #[Route('/book/update/{id}', name: 'book_update', methods: ['POST'])]
    public function update(Request $r, $id, ValidatorInterface $validator): Response
    {  
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('create_author', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Blogas Token CSRF');
            return $this->redirectToRoute('author_create');
        }
        
        $book = $this->getDoctrine()
        ->getRepository(Book::class)
        ->find($id);

        $author = $this->getDoctrine()
        ->getRepository(Author::class)
        ->find($r->request->get('books_author'));

        $book
        ->setTitle($r->request->get('book_title'))
        ->setIsbn($r->request->get('book_isbn'))
        ->setPages($r->request->get('book_pages'))
        ->setShortDescription($r->request->get('book_short_description'))
        ->setAuthor($author);

        $errors = $validator->validate($book);

        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());

            }

            return $this->redirectToRoute('book_create');
        }

        if(null === $author) {
            return $this->redirectToRoute('book_create');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('book_index');
    }
 
    #[Route('/book/delete/{id}', name: 'book_delete', methods: ['POST'])]
    public function delete($id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $book = $this->getDoctrine()
        ->getRepository(Book::class)
        ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('book_index');
    }
}
