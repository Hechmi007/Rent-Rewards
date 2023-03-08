<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;
use App\Form\WalletType;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wallet')]
class WalletController extends AbstractController
{
    #[Route('/', name: 'app_wallet_index', methods: ['GET'])]
    public function index(Request $request, WalletRepository $walletRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $wallet = $walletRepository->findOneBy(['username' => $user]);
        // check if the user already has a wallet
        if (!$wallet) {
            // create a new wallet object
    $wallet = new Wallet();
    $wallet->setUsername($user);


// update the wallet properties
$wallet->setSolde(0);
$wallet->setPoints(0);
$wallet->setPlan('basic');

// save the wallet
$entityManager = $this->getDoctrine()->getManager();
$entityManager->persist($wallet);
$entityManager->flush();
            $this->addFlash('success', 'Wallet created successfully!');
        } else {
            // redirect the user to the show page for the existing wallet
            return $this->redirectToRoute('app_wallet_show', ['id' => $wallet->getId()]);
        }

/*         $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wallet);
            $entityManager->flush();

            $this->addFlash('success', 'Wallet updated successfully!');

            return $this->redirectToRoute('app_wallet_index');
        }
 */
        return $this->render('wallet/index.html.twig', [
            'wallet' => $wallet,
           // 'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/show', name: 'app_wallet_show', methods: ['GET', 'POST'])]
   public function show(Wallet $wallet): Response
   {
       return $this->render('wallet/show.html.twig', [
           'wallet' => $wallet,
       ]);
   }
    #[Route('/{id}/edit', name: 'app_wallet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wallet $wallet, WalletRepository $walletRepository): Response
    {
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $walletRepository->save($wallet, true);

            return $this->redirectToRoute('app_wallet_index');
        }

        return $this->renderForm('wallet/edit.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
        ]);
    }

    public function delete(EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // delete the user's wallet
        $wallet = $user->getWallet();
        $entityManager->remove($wallet);

        // delete the user
        $entityManager->remove($user);

        $entityManager->flush();

        $this->addFlash('success', 'Account deleted successfully!');

        return $this;
    }

    private function createWalletForUser(User $user): Wallet
    {
        $wallet = new Wallet();
        $wallet->setUsername($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($wallet);
        $entityManager->flush();

        return $wallet;
    }
}
