<?php

namespace App\Controller;

use App\Entity\PayementCredit;
use App\Form\PayementCreditType;
use App\Repository\PayementCreditRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payement/credit')]
class PayementCreditController extends AbstractController
{
    #[Route('/', name: 'app_payement_credit_index', methods: ['GET'])]
    public function index(PayementCreditRepository $payementCreditRepository): Response
    {
        return $this->render('payement_credit/index.html.twig', [
            'payement_credits' => $payementCreditRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_payement_credit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PayementCreditRepository $payementCreditRepository): Response
    {
        $payementCredit = new PayementCredit();
        $form = $this->createForm(PayementCreditType::class, $payementCredit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payementCreditRepository->save($payementCredit, true);

            return $this->redirectToRoute('app_payement_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payement_credit/new.html.twig', [
            'payement_credit' => $payementCredit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payement_credit_show', methods: ['GET'])]
    public function show(PayementCredit $payementCredit): Response
    {
        return $this->render('payement_credit/show.html.twig', [
            'payement_credit' => $payementCredit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payement_credit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PayementCredit $payementCredit, PayementCreditRepository $payementCreditRepository): Response
    {
        $form = $this->createForm(PayementCreditType::class, $payementCredit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payementCreditRepository->save($payementCredit, true);

            return $this->redirectToRoute('app_payement_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payement_credit/edit.html.twig', [
            'payement_credit' => $payementCredit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payement_credit_delete', methods: ['POST'])]
    public function delete(Request $request, PayementCredit $payementCredit, PayementCreditRepository $payementCreditRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payementCredit->getId(), $request->request->get('_token'))) {
            $payementCreditRepository->remove($payementCredit, true);
        }

        return $this->redirectToRoute('app_payement_credit_index', [], Response::HTTP_SEE_OTHER);
    }
}
