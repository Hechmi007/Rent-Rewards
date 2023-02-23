<?php

namespace App\Controller;
use App\Repository\PostRepository;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/dashboards')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    #[Route('/postback', name: 'app_dashboard_post', methods: ['GET'])]
    public function posts(PostRepository $postRepository): Response
    {
        return $this->render('dashboard/post.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }
    #[Route('/toggle_visibility/{id}', name: 'app_toggle_visibility', methods: ['POST'])]
    public function toggleVisibility(Post $post): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post->setVisible(!$post->isVisible());
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->redirectToRoute('app_dashboard_post', [], Response::HTTP_SEE_OTHER);
    }
}
