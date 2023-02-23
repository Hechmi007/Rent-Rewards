<?php

namespace App\Controller;

use App\Entity\FidelityCard;
use App\Form\FidelityCardType;
use App\Repository\FidelityCardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fidelity/card')]
class FidelityCardController extends AbstractController
{
    #[Route('/', name: 'app_fidelity_card_index', methods: ['GET'])]
    public function index(FidelityCardRepository $fidelityCardRepository): Response
    {
        return $this->render('fidelity_card/index.html.twig', [
            'fidelity_cards' => $fidelityCardRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fidelity_card_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FidelityCardRepository $fidelityCardRepository): Response
    {
        $fidelityCard = new FidelityCard();
        $form = $this->createForm(FidelityCardType::class, $fidelityCard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fidelityCardRepository->save($fidelityCard, true);

            return $this->redirectToRoute('app_fidelity_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fidelity_card/new.html.twig', [
            'fidelity_card' => $fidelityCard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fidelity_card_show', methods: ['GET'])]
    public function show(FidelityCard $fidelityCard): Response
    {
        return $this->render('fidelity_card/show.html.twig', [
            'fidelity_card' => $fidelityCard,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fidelity_card_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FidelityCard $fidelityCard, FidelityCardRepository $fidelityCardRepository): Response
    {
        $form = $this->createForm(FidelityCardType::class, $fidelityCard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fidelityCardRepository->save($fidelityCard, true);

            return $this->redirectToRoute('app_fidelity_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fidelity_card/edit.html.twig', [
            'fidelity_card' => $fidelityCard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fidelity_card_delete', methods: ['POST'])]
    public function delete(Request $request, FidelityCard $fidelityCard, FidelityCardRepository $fidelityCardRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fidelityCard->getId(), $request->request->get('_token'))) {
            $fidelityCardRepository->remove($fidelityCard, true);
        }

        return $this->redirectToRoute('app_fidelity_card_index', [], Response::HTTP_SEE_OTHER);
    }
}
