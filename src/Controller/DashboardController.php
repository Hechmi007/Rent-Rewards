<?php

namespace App\Controller;
use App\Repository\PostRepository;

use App\Entity\Post;
use App\Repository\CreditRepository;
use App\Repository\ReportRepository;
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
    
    #[Route('/toggle_visibility/{id}/{back?app_dashboard_post}', name: 'app_toggle_visibility', methods: ['POST'])]
    public function toggleVisibility(Post $post,$back): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post->setVisible(!$post->isVisible());
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->redirectToRoute($back, [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/reportback', name: 'app_dashboard_report', methods: ['GET'])]
    public function report(ReportRepository $reportRepository): Response
    {
        return $this->render('dashboard/report.html.twig', [
            'reports' => $reportRepository->findAll(),
        ]);
    }
    #[Route('/postback', name: 'app_dashboard_post', methods: ['GET'])]
    public function posts(PostRepository $postRepository): Response
    {
        return $this->render('dashboard/post.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }
}
