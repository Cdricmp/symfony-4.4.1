<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Commentaire;
use App\Form\CommentaireType;


/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('App:Post')->getLastInsertId('App:Post',3);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if(!$this->getUser()){
            $this->addFlash('notice','Vous devez être identifiez pour accéder a cet section');
            
            return $this->redirectToRoute('post_index');
        }

        

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setUser($this->getUser());

            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('add','L\'article a bien été ajouté !');

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET","POST"})
     */
    public function show(Post $post, Request $request): Response
    {
        // return $this->render('post/show.html.twig', [
        //     'post' => $post,
        // ]);

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if(!$this->getUser()){
            $this->addFlash('com','Vous devez être identifiez pour commenter');
            
            return $this->redirectToRoute('commentaire_index');
        }

        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            $commentaire->setUser($this->getUser());
            $commentaire->setPost($post);

            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }
        $commentaires = $entityManager->getRepository('App:Commentaire')->findByPost($post);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'commentaires' => $commentaires,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * @Route("/api/post/", name="api_post_index", methods={"GET"})
     */
    public function apiIndex()
    {
        $em = $this->getDoctrine()->getManager();
        
        $posts = $em->getRepository('App:Post')->getLastInsertedAjax('App:Post', 3);

        return new JsonResponse(array(
            'posts' => $posts
        ));
    }
}
