<?php

namespace App\Controller;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Entity\Donation;
use App\Form\DonationType;
use App\Repository\DonationRepository;
use Dompdf\Dompdf;
use Dompdf\Options;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/donation')]
class DonationController extends AbstractController
{
    #[Route('/', name: 'app_donation_index', methods: ['GET'])]
    public function index(DonationRepository $donationRepository): Response
    {
        return $this->render('donation/index.html.twig', [
            'donations' => $donationRepository->findAll(),
        ]);
    }
    //lhne 3mlna route jdida bch nrak7oo feha pdf 
    #[Route('/pdf', name: 'app_donation_pdf', methods: ['GET'])]
    public function pdf(DonationRepository $donationRepository): Response
    {   
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFront', 'Arial');//lhne 3mlne type te3 font

        
        $dompdf = new Dompdf($pdfOptions); // nouveau pdf ajoute
        

        $html = $this->renderView('donation/listD.html.twig', [ 'donations' => $donationRepository->findAll(),]);// lezem etdakhelha hedhii bch yemchii 
        $dompdf->loadHtml($html);//lhne lhtml l bch yokhrej fl fichier html.twig bch ywalli pdf 
        $dompdf->setPaper('A4', 'portrait');// lhne bch ndakhell type te3 papier
        $dompdf->render();//lhne bch n7otthom
        $dompdf->stream('donationlist.pdf', ['Attachement' => true]);//lhne bch yetsab automatique w dakhalna esm fichier.pdf

        return $this->render('donation/index.html.twig', [
            'donations' => $donationRepository->findAll(),// lhne bch etdakhell les informations bch yekhdem twigg kima les routes lokhrin
        ]);
    }

    #[Route('/new', name: 'app_donation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DonationRepository $donationRepository): Response
    {
        $donation = new Donation();
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donationRepository->save($donation, true);

            return $this->redirectToRoute('app_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donation/new.html.twig', [
            'donation' => $donation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donation_show', methods: ['GET'])]
    public function show(Donation $donation): Response
    {
        return $this->render('donation/show.html.twig', [
            'donation' => $donation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_donation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Donation $donation, DonationRepository $donationRepository): Response
    {
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donationRepository->save($donation, true);

            return $this->redirectToRoute('app_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donation/edit.html.twig', [
            'donation' => $donation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donation_delete', methods: ['POST'])]
    public function delete(Request $request, Donation $donation, DonationRepository $donationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $donation->getId(), $request->request->get('_token'))) {
            $donationRepository->remove($donation, true);
        }

        return $this->redirectToRoute('app_donation_index', [], Response::HTTP_SEE_OTHER);
    }
}
