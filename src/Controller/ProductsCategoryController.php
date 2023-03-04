<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\ProductsCategory;
use App\Form\ProductsCategoryType;
use App\Repository\ProductsCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products/category')]
class ProductsCategoryController extends AbstractController
{
    #[Route('/', name: 'app_products_category_index', methods: ['GET'])]
    public function index(ProductsCategoryRepository $ProductsCategoryRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $productRepository = $entityManager->getRepository(ProductsCategory::class);
        
        $queryBuilder = $ProductsCategoryRepository->createQueryBuilder('pc')
            ->orderBy('pc.id', 'DESC');
        
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('products_category/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_products_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductsCategoryRepository $productsCategoryRepository): Response
    {
        $productsCategory = new ProductsCategory();
        $form = $this->createForm(ProductsCategoryType::class, $productsCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsCategoryRepository->save($productsCategory, true);

            return $this->redirectToRoute('app_products_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products_category/new.html.twig', [
            'products_category' => $productsCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_category_show', methods: ['GET'])]
    public function show(ProductsCategory $productsCategory): Response
    {
        return $this->render('products_category/show.html.twig', [
            'products_category' => $productsCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_products_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductsCategory $productsCategory, ProductsCategoryRepository $productsCategoryRepository): Response
    {
        $form = $this->createForm(ProductsCategoryType::class, $productsCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsCategoryRepository->save($productsCategory, true);

            return $this->redirectToRoute('app_products_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products_category/edit.html.twig', [
            'products_category' => $productsCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_category_delete', methods: ['POST'])]
    public function delete(Request $request, ProductsCategory $productsCategory, ProductsCategoryRepository $productsCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productsCategory->getId(), $request->request->get('_token'))) {
            $productsCategoryRepository->remove($productsCategory, true);
        }

        return $this->redirectToRoute('app_products_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
