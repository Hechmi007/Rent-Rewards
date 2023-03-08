<?php
namespace App\Controller;

use App\Entity\Wallet;
use App\Form\WalletType;
use App\Repository\WalletRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/wallet")
 */
class AdminWalletController extends AbstractController
{
    /**
     * @Route("/", name="admin_wallet_index", methods={"GET"})
     */
    public function index(WalletRepository $walletRepository): Response
    {
        $wallets = $walletRepository->findAll();
        return $this->render('admin/wallet/index.html.twig', [
            'wallets' => $wallets,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_wallet_show", methods={"GET"})
     */
    public function show(Wallet $wallet): Response
    {
        return $this->render('admin/wallet/show.html.twig', [
            'wallet' => $wallet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_wallet_edit", methods={"GET"})
     */
    public function edit(Request $request, Wallet $wallet): Response
    {
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
    
            return $this->redirectToRoute('wallet_index');
        }
    
        return $this->render('admin/wallet/edit.html.twig', [
            'wallet' => $wallet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_wallet_update", methods={"POST"})
     */
    public function update(Request $request, Wallet $wallet): Response
    {
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_wallet_index');
        }

        return $this->render('admin/wallet/edit.html.twig', [
            'wallet' => $wallet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_wallet_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Wallet $wallet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wallet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wallet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_wallet_index');
    }
}
