<?php

namespace App\Controller;

use App\Entity\Charitycategory;
use App\Form\CharitycategoryType;
use App\Repository\CharitycategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/charitycategory')]
class CharitycategoryController extends AbstractController
{
    #[Route('/', name: 'app_charitycategory_index', methods: ['GET'])]
    public function index(CharitycategoryRepository $charitycategoryRepository): Response
    {
        return $this->render('charitycategory/index.html.twig', [
            'charitycategories' => $charitycategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_charitycategory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CharitycategoryRepository $charitycategoryRepository): Response
    {
        $charitycategory = new Charitycategory();
        $form = $this->createForm(CharitycategoryType::class, $charitycategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $charitycategoryRepository->save($charitycategory, true);

            return $this->redirectToRoute('app_charitycategory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('charitycategory/new.html.twig', [
            'charitycategory' => $charitycategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_charitycategory_show', methods: ['GET'])]
    public function show(Charitycategory $charitycategory): Response
    {
        return $this->render('charitycategory/show.html.twig', [
            'charitycategory' => $charitycategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_charitycategory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Charitycategory $charitycategory, CharitycategoryRepository $charitycategoryRepository): Response
    {
        $form = $this->createForm(CharitycategoryType::class, $charitycategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $charitycategoryRepository->save($charitycategory, true);

            return $this->redirectToRoute('app_charitycategory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('charitycategory/edit.html.twig', [
            'charitycategory' => $charitycategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_charitycategory_delete', methods: ['POST'])]
    public function delete(Request $request, Charitycategory $charitycategory, CharitycategoryRepository $charitycategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $charitycategory->getId(), $request->request->get('_token'))) {
            $charitycategoryRepository->remove($charitycategory, true);
        }

        return $this->redirectToRoute('app_charitycategory_index', [], Response::HTTP_SEE_OTHER);
    }
}
