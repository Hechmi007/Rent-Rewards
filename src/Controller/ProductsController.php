<?php

namespace App\Controller;

use App\Form\ProductSearchType;
use Stripe;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Khill\Lavacharts\Lavacharts;

#[Route('/products')]
class ProductsController extends AbstractController
{   
    #[Route('/{id}/thankyou', name: 'app_products_success', methods: ['GET'])]
    public function success($id, ProductsRepository $ProductsRepository): Response {
    
        return $this->render('products/success.html.twig',['products' => $ProductsRepository->find($id),]);
    }

    #[Route('/stripe', name: 'app_products_stripe')]
    public function stripeindex(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
        return $this->render('products/show.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }

 
    #[Route('/{id}/stripe/create-charge', name: 'app_products_charge', methods: ['POST'])]
    public function createCharge($id,ProductsRepository $ProductsRepository, Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create ([
                "amount" => 50000,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test",
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('app_products_success', ['id' => $id], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/pdf', name: 'app_products_pdf', methods: ['GET'])]
    public function pdf($id,Products $product): Response {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFront', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('products/facture.html.twig', ['product' => $product]);
        $dompdf->loadHtml($html); 
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return new Response (
            $dompdf->stream('RentRewardsInvoice23', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );    
    }

    #[Route('/', name: 'app_products_index', methods: ['GET'])]
    public function index(ProductsRepository $productsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $productRepository = $entityManager->getRepository(Products::class);
        
        $queryBuilder = $productRepository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');
        
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5 // number of items per page
        );
        
        return $this->render('products/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    

    #[Route('/view', name: 'app_products_View', methods: ['GET','POST'])]
    public function View(Request $request, ProductsRepository $ProductsRepository): Response
    {
        $products = $ProductsRepository->findAll();
        $form = $this->createForm(ProductSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchquery = $form->getData()['productName'];
            $products = $ProductsRepository->search($searchquery);
        }
        return $this->render('products/view.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);


    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductsRepository $productsRepository, SluggerInterface $slugger): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsRepository->save($product, true);
            $ProductPicture = $form->get('ProductPicture')->getData();
            if ($ProductPicture) {
                $originalFilename  = pathinfo($ProductPicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$ProductPicture->guessExtension();

                try {
                    $ProductPicture->move(
                        $this->getParameter('productpicture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $product->setProductPicture($newFilename);
            }
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
            
        ]);
        
    }


    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, ProductsRepository $productsRepository): Response
    {
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsRepository->save($product, true);

            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_products_delete', methods: ['POST'])]
    public function delete(Request $request, Products $product, ProductsRepository $productsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productsRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
    }
}
