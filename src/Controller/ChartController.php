<?php

namespace App\Controller;
use App\Entity\Products;
use App\Repository\ProductsRepository;
use App\Repository\ProductsCategoryRepository;
use App\Entity\ProductsCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chart', name: 'app_chart')]
class ChartController extends AbstractController
{
    public function index(ProductsRepository $ProductsRepository, ProductsCategoryRepository $ProductsCategoryRepository): Response
    {
        $categories=$ProductsCategoryRepository->findAll();
        foreach($categories as $c)
        {
            $catnom [] =$c->getCategoryname();
            $nbr [] = $ProductsRepository->getNombreAchatsPourCategorie($c->getId());
        }
        return $this->render('chart/index.html.twig', [
            'achats' => $ProductsRepository->findAll(),
            'catnom' => json_encode($catnom),
            'nbr' => json_encode($nbr),
        ]);
    }}
