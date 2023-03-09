<?php

namespace App\Controller;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Entity\CharityDemand;
use App\Entity\Donation;
use App\Form\DonationType;
use App\Repository\DonationRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use symfony\component\Serializer\Normalizer\NormalizableInterface;
use symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;
use Twilio\Rest\Client;

#[Route('/donation')]
class DonationController extends AbstractController
{
    #[Route('/', name: 'app_donation_index', methods: ['GET'])]
    public function index(DonationRepository $donationRepository, PaginatorInterface  $paginator, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $donationRepository = $entityManager->getRepository(Donation::class);

        $queryBuilder = $donationRepository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5 // number of items per page
        );

        return $this->render('donation/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    #[Route("/AllDonations", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getDonation(DonationRepository $repo, SerializerInterface $serializer)
    {
        $donations = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

        $json = $serializer->serialize($donations, 'json', ['groups' => "donation"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }


    #[Route("/AllDonations/{id}", name: "donation")]
    public function DemandsId($id, NormalizerInterface $normalizer, DonationRepository $repo)
    {
        $donation = $repo->find($id);
        $donationNormalises = $normalizer->normalize($donation, 'json', ['groups' => "donation"]);
        return new Response(json_encode($donationNormalises));
    }
    #[Route("/addDonationJSON/new", name: "addDonationJSON")]
    public function addSDemandJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $donation = new Donation();
        $donation->setPointsdonated($req->get('Title'));
        $donation->setDatedonation(new \DateTime($req->get('date')));

        $em->persist($donation);
        $em->flush();

        $jsonContent = $Normalizer->normalize($donation, 'json', ['groups' => "donation"]);
        return new Response(json_encode($jsonContent));
        //https://127.0.0.1:8000/charity/demand/addDemandsJSON/new?Title=wael~&receiver=wael&pointsdemanded=11111&datedemande=2023-11-12&file=ILLUSTRATION2-63fe33d4e13a4.jpg
    }

    #[Route("/updateDonationJSON/{id}", name: "updateDonationJSON")]
    public function updateDemandJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $donation  = $em->getRepository(Donation::class)->find($id);
        $donation = new Donation();
        $donation->setPointsdonated($req->get('Title'));
        $donation->setDatedonation(new \DateTime($req->get('date')));

        $em->flush();

        $jsonContent = $Normalizer->normalize($donation, 'json', ['groups' => 'donation']);
        return new Response("Donation updated successfully " . json_encode($jsonContent));
    }




    #[Route("/deleteDonationJSON/{id}", name: "deleteDonationJSON")]
    public function deleteDonationJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $donation = $em->getRepository(CharityDemand::class)->find($id);
        $em->remove($donation);
        $em->flush();
        $jsonContent = $Normalizer->normalize($donation, 'json', ['groups' => 'donation']);
        return new Response("donation deleted successfully " . json_encode($jsonContent));
    }







    //lhne 3mlna route jdida bch nrak7oo feha pdf 
    #[Route('/pdf', name: 'app_donation_pdf', methods: ['GET'])]
    public function pdf(DonationRepository $donationRepository): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFront', 'Arial'); //lhne 3mlne type te3 font


        $dompdf = new Dompdf($pdfOptions); // nouveau pdf ajoute


        $html = $this->renderView('donation/listD.html.twig', ['donations' => $donationRepository->findAll(),]); // lezem etdakhelha hedhii bch yemchii 
        $dompdf->loadHtml($html); //lhne lhtml l bch yokhrej fl fichier html.twig bch ywalli pdf 
        $dompdf->setPaper('A4', 'portrait'); // lhne bch ndakhell type te3 papier
        $dompdf->render(); //lhne bch n7otthom
        $dompdf->stream('donationlist.pdf', ['Attachement' => true]); //lhne bch yetsab automatique w dakhalna esm fichier.pdf

        return $this->render('donation/index.html.twig', [
            'donations' => $donationRepository->findAll(), // lhne bch etdakhell les informations bch yekhdem twigg kima les routes lokhrin
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
            $sid = 'AC1d3698dee78adbebcc6f77f1fb18346c';
            $token = 'ed2bc0fcee11a4cb95dc365f4986dd60';
            $from = '+15076783752';
            $twilio = new Client($sid, $token);

            //     $message = $twilio->messages 
            //                       ->create("+21699027754", // to 
            //                                array(  
            //                                    "messagingServiceSid" => "MGcbf9acd4611dfe8faf4a1265c0c05112",      
            //                                    "body" => "Votre Commande est validé" 
            //                                ) 
            //                       ); 
            //                       dump("ones ");  
            //  dump($message->sid);  



            $message = $twilio->messages
                ->create(
                    "+21623875468", // to
                    array(
                        "from" => "+15076783752",
                        "body" => "AAA",
                    )
                );
        }

        return $this->renderForm('donation/new.html.twig', [
            'donation' => $donation,
            'form' => $form,
        ]);
    }







    /* 
    #[Route('/new', name: 'app_donation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DonationRepository $donationRepository): Response
    {

        $donation = new Donation();
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donationRepository->save($donation, true);

            //lina code sms
// badil fil services o fil nottifier o badil token will 
            return $this->redirectToRoute('app_donation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donation/new.html.twig', [
            'donation' => $donation,
            'form' => $form,
        ]);
    } */
    /*public function makeDonation($Points, $charityPoints)
    {
        if ($walletPoints >= $charityPoints) {
            // Donation is possible
            $walletPoints -= $charityPoints; // Reduce points from wallet
            $message = "Donation successful! {$charityPoints} points have been donated.";
        } else {
            // Donation is not possible
            $message = "Donation failed. Insufficient points in wallet.";
        }
        return $message;
    } */
     #[Route('/new/{id}', name: 'app_charity_demand_todonate', methods: ['GET', 'POST'])]
    public function ToDonate(Request $request, DonationRepository $donationRepository, CharityDemand $charityDemand): Response
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
            'charity_demand' => $charityDemand,
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
