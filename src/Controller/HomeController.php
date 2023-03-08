<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $req): Response
    {   $session=$req->getSession();
        $user=$session->get('user');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user'=>$user
        ]);
    }
    
    public function base(Request $req): Response
    {   $session=$req->getSession();
        $user=$session->get('user');
        $role=$user->getRoles();
        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
            'Roles'=>$role
        ]);
    }
}
