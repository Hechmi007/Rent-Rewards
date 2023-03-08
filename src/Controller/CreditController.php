<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Form\CreditType;
use App\Repository\CreditRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/credit')]
class CreditController extends AbstractController
{
    #[Route('/', name: 'app_credit_index', methods: ['GET'])]
    public function index(CreditRepository $creditRepository, Security $security): Response
    {
        $user = $security->getUser();
    $credits = $creditRepository->findBy(['user' => $user]);

    return $this->render('credit/index.html.twig', [
        'credits' => $credits,
    ]);
    }

    #[Route('/new', name: 'app_credit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CreditRepository $creditRepository,Security $security): Response
    {
        $credit = new Credit();
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $credit->setUser($security->getUser());
            $credit->setStatus('Pending');
            $credit->setCreatedAt(new \DateTime());
            $creditRepository->save($credit, true);

            return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('credit/new.html.twig', [
            'credit' => $credit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_credit_show', methods: ['GET'])]
    public function show(Credit $credit): Response
    {
        return $this->render('credit/show.html.twig', [
            'credit' => $credit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_credit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Credit $credit, CreditRepository $creditRepository): Response
    {
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $creditRepository->save($credit, true);

            return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('credit/edit.html.twig', [
            'credit' => $credit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_credit_delete', methods: ['POST'])]
    public function delete(Request $request, Credit $credit, CreditRepository $creditRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$credit->getId(), $request->request->get('_token'))) {
            $creditRepository->remove($credit, true);
        }

        return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/credit/{id}/accept', name: 'app_credit_accept', methods: ['POST'])]
public function accept(Request $request, Credit $credit, CreditRepository $creditRepository): Response
{
    // Make sure only the admin of the credit can accept it
    //$this->denyAccessUnlessGranted('edit', $credit);
    $entityManager = $this->getDoctrine()->getManager();
    // Update the status of the credit and save to the database
    $credit->setStatus("Accepted");
    //$credit->setMontant(2000);
    $entityManager->persist($credit);
    $entityManager->flush();
    // Update the status of the credit and save to the database
   
    // Redirect back to the credit index page
    return $this->redirectToRoute('app_dashboard_credit', [], Response::HTTP_SEE_OTHER);
}

#[Route('/credit/{id}/reject', name: 'app_credit_reject', methods: ['POST'])]
public function reject(Request $request, Credit $credit, CreditRepository $creditRepository): Response
{
    // Make sure only the owner of the credit can reject it
    //$this->denyAccessUnlessGranted('edit', $credit);
    $entityManager = $this->getDoctrine()->getManager();
    // Update the status of the credit and save to the database
    $credit->setStatus("Rejected");
    //$credit->setMontant(2000);
    $entityManager->persist($credit);
    $entityManager->flush();
   // $creditRepository->save($credit);

    // Redirect back to the credit index page
    return $this->redirectToRoute('app_dashboard_credit', [], Response::HTTP_SEE_OTHER);
}
}
