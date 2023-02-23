<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\PostType;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Security\Core\Security;


#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository, Security $security): Response
    {
        $user = $security->getUser();

        
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
            'username' => $user,
            
        ]);
    }
    #[Route('/showall', name: 'app_show_all', methods: ['GET'])]
    public function showall(PostRepository $postRepository): Response
    {
        
        return $this->render('post/show_all.html.twig', [
            'posts' => $postRepository->findBy(['visible' => true]),
            
        ]);
        
      

    
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository, Security $security): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedat(new DateTime());
            $post->setRating(0);
            $post->setUsername($security->getUser());
            $post->setVisible(true);
            $postRepository->save($post, true);

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Post $post,Security $security): Response
    {
       
    
        $CommentRepository = $this->getDoctrine()->getRepository(Comment::class);

        $comment= new Comment();
        $commentform = $this->createForm(CommentType::class, $comment);
        
        $commentform->handleRequest($request);
        if ($commentform->isSubmitted() && $commentform->isValid()) {
            $comment->setIDPost($post);
            $comment->setUpvotes(0);
            $comment->setCreatedatcomment(new DateTime());
            $comment->setUsername($security->getUser());
            $CommentRepository->save($comment, true);

            
           /*  $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush(); */

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }
        if($post->isVisible() == false){
            return $this->redirectToRoute('app_show_all', [], Response::HTTP_SEE_OTHER);
        }
        /* if (!$post) {
            // Invalid post ID, redirect to app_show_all
            return $this->redirectToRoute('app_show_all', [], Response::HTTP_SEE_OTHER);
        } */
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $CommentRepository->findBy(['IDPost' => $post->getId()]),
            'comment_form' => $commentform->createView(),
            'user'=>$security->getUser(),
        ]);

    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->save($post, true);

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
