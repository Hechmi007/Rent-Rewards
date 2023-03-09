<?php

namespace App\Controller;

use App\Entity\FidelityCard;
use App\Form\FidelityCardType;
use App\Repository\FidelityCardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use Symfony\Component\Mime\Email; // Import the email class
use Symfony\Component\Mailer\MailerInterface; // Import the mailer interface

use symfony\component\Serializer\Normalizer\NormalizableInterface;
use symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;
#[Route('/fidelity/card')]
class FidelityCardController extends AbstractController
{


    #[Route('/', name: 'app_fidelity_card_index', methods: ['GET'])]
    public function index(FidelityCardRepository $fidelityCardRepository,PaginatorInterface $paginator, Request $request): Response
    {
     $donnees = $fidelityCardRepository->findAll();
                 $fidelity_cards = $paginator->paginate(
                     $donnees,
                     $request->query->getInt('page', 1),
                     10
                 );
            return $this->render('fidelity_card/index.html.twig', [
            'fidelity_cards' => $fidelity_cards,

        ]);

     $recipientEmail = 'monrezult@gmail.com';
            $this->sendEmail($mailer, $recipientEmail);


    }


#[Route('/send-email')]
public function sendEmail(MailerInterface $mailer)
{

    $email = (new Email())
        ->from('monrezult@gmail.com')
        ->to('monrezult@gmail.com')
        ->subject('Test email')
        ->text('This is a test email sent from Symfony.')
        ->html( '<p>The card has been added successfully.</p>',
                       'text/html');

        $mailer->send($email);
if ($mailer->send($email)) {
    $message = 'Email sent successfully.';
} else {
    $message = 'Unable to send email.';
}
    return $this->render('email/sent.html.twig', [
        'message' => $message,
    ]);



    }




    #[Route("/Allcards", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository,
    //* avec la méthode d'injection de dépendances.
    public function getcard(FidelityCardRepository $repo, SerializerInterface $serializer)
    {
        $fidelityCard = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

        $json = $serializer->serialize($fidelityCard, 'json', ['groups' => "card"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }
    #[Route("/Allcards/{id}", name: "card")]
    public function paquetId($id, NormalizerInterface $normalizer, FidelityCardRepository $repo)
    {
        $fidelityCard = $repo->find($id);
        $fidelityCardNormalises = $normalizer->normalize($fidelityCard, 'json', ['groups' => "card"]);
        return new Response(json_encode($fidelityCardNormalises));
    }
    #[Route("/addcardJSON/new", name: "addcardJSON")]
    public function addcardJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $fidelityCard = new FidelityCard();
        $fidelityCard->setNumcarte($req->get('nom'));

        $fidelityCard->setDatedebut(new \DateTime($req->get('dated')));
        $fidelityCard->setDatefin(new \DateTime($req->get('datef')));

        $em->persist($fidelityCard);
        $em->flush();

        $jsonContent = $Normalizer->normalize($fidelityCard, 'json', ['groups' => "card"]);
        return new Response(json_encode($jsonContent));
        //https://127.0.0.1:8000/charity/demand/addDemandsJSON/new?Title=wael~&receiver=wael&pointsdemanded=11111&datedemande=2023-11-12&file=ILLUSTRATION2-63fe33d4e13a4.jpg
    }
    
    #[Route("/updatecardJSON/{id}", name: "updatecardJSON")]
    public function updatecardJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $fidelityCard = $em->getRepository(FidelityCard::class)->find($id);
        $fidelityCard = new FidelityCard();
        $fidelityCard->setNumcarte($req->get('nom'));
        
        $fidelityCard->setDatedebut(new \DateTime($req->get('dated')));
        $fidelityCard->setDatefin(new \DateTime($req->get('datef')));
        

        $em->flush();

        $jsonContent = $Normalizer->normalize( $fidelityCard, 'json', ['groups' => "card"]);
        return new Response("card updated successfully " . json_encode($jsonContent));
    }




    #[Route("/deletecardJSON/{id}", name: "deletecardJSON")]
    public function deleteDemandJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $fidelityCard = $em->getRepository(FidelityCard::class)->find($id);
        $em->remove($fidelityCard);
        $em->flush();
        $jsonContent = $Normalizer->normalize($fidelityCard, 'json', ['groups' => "card"]);
        return new Response("card deleted successfully " . json_encode($jsonContent));



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
                $mailer->send($email);

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
