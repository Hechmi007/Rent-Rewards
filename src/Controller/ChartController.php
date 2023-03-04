<?php

namespace App\Controller;
use App\Entity\Products;
use App\Entity\ProductsCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Khill\Lavacharts\Lavacharts;

#[Route('/chart', name: 'app_chart')]
class ChartController extends AbstractController
{
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productCounts = $em->createQueryBuilder()
            ->select('COUNT(p.id) as count, pc.id as productscategory')
            ->from(Products::class, 'p')
            ->join('p.category', 'pc')
            ->groupBy('pc.id')
            ->getQuery()
            ->getResult();

        return $this->render('chart/index.html.twig', [
            'productCounts' => $productCounts,

        ]);
    }
}
