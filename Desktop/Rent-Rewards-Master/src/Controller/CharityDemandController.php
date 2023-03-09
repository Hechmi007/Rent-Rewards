<?php


namespace App\Controller;

use App\Entity\CharityDemand;
use App\Form\CharityDemandType;
use App\Repository\CharityDemandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/charity/demand')]
class CharityDemandController extends AbstractController
{
    #[Route('/', name: 'app_charity_demand_index', methods: ['GET'])]
    public function index(CharityDemandRepository $charityDemandRepository): Response
    {
        return $this->render('charity_demand/index.html.twig', [
            'charity_demands' => $charityDemandRepository->findAll(),
        ]);
    }
    #[Route('/view', name: 'app_charity_demand_View', methods: ['GET'])]
    public function View(CharityDemandRepository $charityDemandRepository): Response
    {




        return $this->render('charity_demand/view.html.twig', [
            'charity_demands' => $charityDemandRepository->findAll(),
        ]);
    }



    #[Route('/pdf', name: 'app_charity_demand_pdf', methods: ['GET'])]
    public function pdf(CharityDemandRepository $charityDemandRepository): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFront', 'Arial'); //lhne 3mlne type te3 font


        $dompdf = new Dompdf($pdfOptions); // nouveau pdf ajoute


        $html = $this->renderView('charity_demand/listD.html.twig', ['charity_demands' => $charityDemandRepository->findAll(),]); // lezem etdakhelha hedhii bch yemchii 
        $dompdf->loadHtml($html); //lhne lhtml l bch yokhrej fl fichier html.twig bch ywalli pdf 
        $dompdf->setPaper('A4', 'portrait'); // lhne bch ndakhell type te3 papier
        $dompdf->render(); //lhne bch n7otthom
        $dompdf->stream('demandslist.pdf', ['Attachement' => true]); //lhne bch yetsab automatique w dakhalna esm fichier.pdf

        return $this->render('charity_demand/index.html.twig', [
            'charity_demands' => $charityDemandRepository->findAll(), // lhne bch etdakhell les informations bch yekhdem twigg kima les routes lokhrin
        ]);
    }


    #[Route('/new', name: 'app_charity_demand_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CharityDemandRepository $charityDemandRepository): Response
    {
        $charityDemand = new CharityDemand();
        $form = $this->createForm(CharityDemandType::class, $charityDemand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $charityDemandRepository->save($charityDemand, true);
            

            return $this->redirectToRoute('app_charity_demand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('charity_demand/new.html.twig', [
            'charity_demand' => $charityDemand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_charity_demand_show', methods: ['GET'])]
    public function show(CharityDemand $charityDemand): Response
    {
        return $this->render('charity_demand/show.html.twig', [
            'charity_demand' => $charityDemand,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_charity_demand_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CharityDemand $charityDemand, CharityDemandRepository $charityDemandRepository): Response
    {
        $form = $this->createForm(CharityDemandType::class, $charityDemand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $charityDemandRepository->save($charityDemand, true);

            return $this->redirectToRoute('app_charity_demand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('charity_demand/edit.html.twig', [
            'charity_demand' => $charityDemand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_charity_demand_delete', methods: ['POST'])]
    public function delete(Request $request, CharityDemand $charityDemand, CharityDemandRepository $charityDemandRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $charityDemand->getId(), $request->request->get('_token'))) {
            $charityDemandRepository->remove($charityDemand, true);
        }

        return $this->redirectToRoute('app_charity_demand_index', [], Response::HTTP_SEE_OTHER);
    }
}
