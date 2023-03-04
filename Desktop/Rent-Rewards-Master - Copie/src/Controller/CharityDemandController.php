<?php


namespace App\Controller;

use App\Entity\CharityDemand;
use App\Entity\Post;
use App\Form\SearchType;
use App\Form\SubmitType;
use App\Form\CharityDemandType;
use App\Repository\CharityDemandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use symfony\component\Serializer\Normalizer\NormalizableInterface;
use symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;




#[Route('/charity/demand')]
class CharityDemandController extends AbstractController

{

    #[Route("/AllDemands", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getDemands(CharityDemandRepository $repo, SerializerInterface $serializer)
    {
        $charityDemands = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

        $json = $serializer->serialize($charityDemands, 'json', ['groups' => "charityDemand"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }


    #[Route("/AllDemands/{id}", name: "demand")]
    public function DemandsId($id, NormalizerInterface $normalizer, CharityDemandRepository $repo)
    {
        $charityDemands = $repo->find($id);
        $demandsNormalises = $normalizer->normalize($charityDemands, 'json', ['groups' => "charityDemand"]);
        return new Response(json_encode($demandsNormalises));
    }
    #[Route("/addDemandsJSON/new", name: "addDemandsJSON")]
    public function addSDemandJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $charityDemand = new charityDemand();
        $charityDemand->setTitle($req->get('Title'));
        $charityDemand->setReceiver($req->get('receiver'));
        $charityDemand->setPointsdemanded($req->get('pointsdemanded'));
        $charityDemand->setDatedemand(new \DateTime($req->get('date')));
        $charityDemand->setFileUpload($req->get('file'));
        $em->persist($charityDemand);
        $em->flush();

        $jsonContent = $Normalizer->normalize($charityDemand, 'json', ['groups' => "charityDemand"]);
        return new Response(json_encode($jsonContent));
        //https://127.0.0.1:8000/charity/demand/addDemandsJSON/new?Title=wael~&receiver=wael&pointsdemanded=11111&datedemande=2023-11-12&file=ILLUSTRATION2-63fe33d4e13a4.jpg
    }

    #[Route("/updateDemandJSON/{id}", name: "updateDemandJSON")]
    public function updateDemandJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $charityDemand  = $em->getRepository(CharityDemand::class)->find($id);
        $charityDemand = new charityDemand();
        $charityDemand->setTitle($req->get('Title'));
        $charityDemand->setReceiver($req->get('receiver'));
        $charityDemand->setPointsdemanded($req->get('pointsdemanded'));
        $charityDemand->setDatedemand(new \DateTime($req->get('date')));
        $charityDemand->setFileUpload($req->get('file'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($charityDemand, 'json', ['groups' => "charityDemand"]);
        return new Response("Demand updated successfully " . json_encode($jsonContent));
    }




    #[Route("/deleteDemandJSON/{id}", name: "deleteDemandJSON")]
    public function deleteDemandJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $charityDemand = $em->getRepository(CharityDemand::class)->find($id);
        $em->remove($charityDemand);
        $em->flush();
        $jsonContent = $Normalizer->normalize($charityDemand, 'json', ['groups' => "charityDemand"]);
        return new Response("Student deleted successfully " . json_encode($jsonContent));
    }

    #[Route('/', name: 'app_charity_demand_index', methods: ['GET', 'POST'])]
    public function index(CharityDemandRepository $charityDemandRepository): Response
    {
        return $this->render('charity_demand/index.html.twig', [
            'charity_demands' => $charityDemandRepository->findAll(),
        ]);
    }
    /*   #[Route('/view', name: 'app_charity_demand_View', methods: ['GET', 'POST'])]
    public function View(CharityDemandRepository $charityDemandRepository): Response
    {




        return $this->render('charity_demand/view.html.twig', [
            'charity_demands' => $charityDemandRepository->findAll(),
        ]);
    } */

    #[Route('/view', name: 'app_charity_demand_View', methods: ['GET', 'POST'])]
    public function View(Request $request, CharityDemandRepository $charityDemandRepository): Response
    {
        $charityDemands = $charityDemandRepository->findAll();
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchquery = $form->getData()['title'];
            $charityDemands = $charityDemandRepository->search($searchquery);
        }
        return $this->render('charity_demand/view.html.twig', [
            'charity_demands' => $charityDemands,
            'form' => $form->createView()
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
    public function new(Request $request, CharityDemandRepository $charityDemandRepository, SluggerInterface $slugger): Response
    {
        $charityDemand = new CharityDemand();
        $form = $this->createForm(CharityDemandType::class, $charityDemand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $FileUpload = $form->get('FileUpload')->getData();
            if ($FileUpload) {
                $originalFilename = pathinfo($FileUpload->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $FileUpload->guessExtension();
                try {
                    $FileUpload->move(
                        $this->getParameter('FileUpload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $charityDemand->setFileUpload($newFilename);
            }








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
