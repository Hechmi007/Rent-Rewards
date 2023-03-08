<?php
 
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;
use App\Repository\ProductsRepository;
use App\Entity\Products;

 
class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
        return $this->render('products/show.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }
    
    #[Route('/{id}/thankyou', name: 'app_products_success', methods: ['GET'])]
    public function success($id,ProductsRepository $ProductsRepository): Response {
    
        return $this->render('products/success.html.twig',['products' => $ProductsRepository->find($id),]);
    }


 
    #[Route('/{id}/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge($id,ProductsRepository $ProductsRepository, Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create ([
                "amount" => 5 * 100,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test",
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('app_success', [], Response::HTTP_SEE_OTHER);
    }
}