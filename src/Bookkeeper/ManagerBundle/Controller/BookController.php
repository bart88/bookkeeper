<?php

namespace Bookkeeper\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Bookkeeper\ManagerBundle\Entity\Book;
use Bookkeeper\ManagerBundle\Form\BookType;


class BookController extends Controller {

  public function indexAction(){
    // get all the books 
    $em = $this->getDoctrine()->getManager();
    // get ALL book(s)
    $books = $em->getRepository('BookkeeperManagerBundle:Book')->findAll();

    return $this->render('BookkeeperManagerBundle:Book:index.html.twig', array(
        'books' => $books 
      ));
  }

  public function showAction($id) {
    // show a book 
    $em = $this->getDoctrine()->getManager();
    $book = $em->getRepository('BookkeeperManagerBundle:Book')->find($id);


    return $this->render('BookkeeperManagerBundle:Book:show.html.twig', array(
      'book' => $book
      ));
  }

  public function newAction() {
    $book = new Book();

    $form = $this->createForm(new BookType(), $book, array(
        'action' => $this->generateUrl('book_create'),
        'method' => 'POST'
      ));

    $form->add('submit', 'submit', array('label' => 'Create Book'));

    return $this->render('BookkeeperManagerBundle:Book:new.html.twig', array(
       'form' => $form->createView()
    ));

  }

  public function createAction(Request $request) {
     // process the request that is specified in the routing.yml file
    $book = new Book();

    $form = $this->createForm(new BookType(), $book, array(
        'action' => $this->generateUrl('book_create'),
        'method' => 'POST'
      ));

    $form->add('submit', 'submit', array('label' => 'Create Book'));

    $form->handleRequest($request);

    if( $form->isValid() ) {
      // entity manager in doctrine
      $em = $this->getDoctrine()->getManager();
      $em->persist($book);
      $em->flush();

      $this->get('session')->getFlashBag()->add('msg', 'Your book has been created');
      // redirect to another page
      return $this->redirect($this->generateUrl('book_show', array(
          'id' => $book->getId()
        )));
    }

    $this->get('session')->getFlashBag()->add('msg', 'Something went wrong');

    return $this->render('BookkeeperManagerBundle:Book:new.html.twig', array(
        'form' => $form->createView()
    ));
   

  }

  public function editAction($id) {
    
    $em = $this->getDoctrine()->getManager();
    // find the book 
    $book = $em->getRepository('BookkeeperManagerBundle:Book')->find($id);

    $form = $this->createForm( new BookType(), $book, array(
        'action' => $this->generateUrl('book_update', array('id' => $book->getId() )),
        'method' => 'PUT'
      ));

    // add submit button
    $form->add('submit', 'submit', array('label' => 'Update Book' ));


    return $this->render('BookkeeperManagerBundle:Book:edit.html.twig', array(
      'form' => $form->createView()
      ));
  }

  public function updateAction(Request $request, $id) {

    $em = $this->getDoctrine()->getManager();
    // find the book 
    $book = $em->getRepository('BookkeeperManagerBundle:Book')->find($id);

    $form = $this->createForm( new BookType(), $book, array(
        'action' => $this->generateUrl('book_update', array('id' => $book->getId() )),
        'method' => 'PUT'
      ));

    // add submit button
    $form->add('submit', 'submit', array('label' => 'Update Book' ));

    // handle and validate the request
    $form->handleRequest($request);


    if($form->isValid()) {
      // save the updated data
      $em->flush();

      $this->get('session')->getFlashBag()->add('msg', 'Your book has been updated');

      return $this->redirect($this->generateUrl('book_show', array('id' => $id )));
    }

    $this->get('session')->getFlashBag()->add('msg', 'Book could not be saved ..');
    // update failed 
    return $this->render('BookkeeperManagerBundle:Book:edit.html.twig', array(
      'form' => $form->createView()
      ));

  }

  public function deleteAction(Request $request, $id){

  }

}
