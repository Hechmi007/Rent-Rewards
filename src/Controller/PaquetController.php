<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Paquet;
use App\Form\PaquetType;
use App\Repository\PaquetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator

use symfony\component\Serializer\Normalizer\NormalizableInterface;
use symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Serializer\SerializerInterface;

#[Route('/paquet')]
class PaquetController extends AbstractController
{

      #[Route('/', name: 'app_paquet_index', methods: ['GET'])]
         public function index(PaquetRepository $paquetRepository, PaginatorInterface $paginator, Request $request): Response
         {
             $donnees = $paquetRepository->findAll();
             $paquets = $paginator->paginate(
                 $donnees,
                 $request->query->getInt('page', 1),
                 8 
             );

             return $this->render('paquet/index.html.twig', [
                 'paquets' => $paquets,
             ]);
         }
    #[Route("/AllPaquets", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getPaquet(PaquetRepository $repo, SerializerInterface $serializer)
    {
        $paquet = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

        $json = $serializer->serialize($paquet, 'json', ['groups' => "paquet"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }
    #[Route("/AllPaquets/{id}", name: "paquet")]
    public function paquetId($id, NormalizerInterface $normalizer, PaquetRepository $repo)
    {
        $paquet = $repo->find($id);
        $paquetNormalises = $normalizer->normalize($paquet, 'json', ['groups' => "paquet"]);
        return new Response(json_encode($paquetNormalises));
    }
    #[Route("/addPaquetsJSON/new", name: "addpaquetsJSON")]
    public function addSpaquetJSON(Request $req,   NormalizerInterface $Normalizer)
    {    

        $em = $this->getDoctrine()->getManager();
        $paquet = new Paquet();
        $paquet->setNomPacks($req->get('nom'));
        $paquet->setDiscribtion($req->get('describition'));
        $paquet->setDateDebutPacks(new \DateTime($req->get('dated')));
        $paquet->setDateFinPacks(new \DateTime($req->get('datef')));
        $paquet->isEtatPacks($req->get('etat'));
        $paquet->setTypePacks($req->get('type'));
        $paquet->setNumcarte($req->get('num'));
        $paquet->setFileUpload($req->get('file'));
        $em->persist($paquet);
        $em->flush();

        $jsonContent = $Normalizer->normalize($paquet, 'json', ['groups' => "paquet"]);
        return new Response(json_encode($jsonContent));
        
    }
    
    #[Route("/updatepaquetJSON/{id}", name: "updatepaquetJSON")]
    public function updatepaquetJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $paquet  = $em->getRepository(Paquet::class)->find($id);
        $paquet = new Paquet();
        $paquet->setNomPacks($req->get('nom'));
        $paquet->setDiscribtion($req->get('describition'));
        $paquet->setDateDebutPacks(new \DateTime($req->get('dated')));
        $paquet->setDateFinPacks(new \DateTime($req->get('datef')));
        $paquet->isEtatPacks($req->get('etat'));
        $paquet->setTypePacks($req->get('type'));
        $paquet->setNumcarte($req->get('num'));
        $paquet->setFileUpload($req->get('file'));

        $em->flush();

        $jsonContent = $Normalizer->normalize( $paquet, 'json', ['groups' => "paquet"]);
        return new Response("paquet updated successfully " . json_encode($jsonContent));
    }




    #[Route("/deleteDpaquetJSON/{id}", name: "deleteDemandJSON")]
    public function deleteDemandJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $paquet = $em->getRepository(CharityDemand::class)->find($id);
        $em->remove($paquet);
        $em->flush();
        $jsonContent = $Normalizer->normalize($paquet, 'json', ['groups' => "paquet"]);
        return new Response("paquet deleted successfully " . json_encode($jsonContent));



    }
    #[Route('/view', name: 'app_paquet_view', methods: ['GET'])]
    public function view(PaquetRepository $paquetRepository): Response
    {
        return $this->render('paquet/view.html.twig', [
            'paquets' => $paquetRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_paquet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaquetRepository $paquetRepository , SluggerInterface $slugger): Response
    {
        $paquet = new Paquet();
        $form = $this->createForm(PaquetType::class, $paquet);
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
                
                $paquet->setFileUpload($newFilename);
            }
            $paquetRepository->save($paquet, true);

            return $this->redirectToRoute('app_paquet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paquet/new.html.twig', [
            'paquet' => $paquet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paquet_show', methods: ['GET'])]
    public function show(Paquet $paquet): Response
    {
        return $this->render('paquet/show.html.twig', [
            'paquet' => $paquet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paquet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paquet $paquet, PaquetRepository $paquetRepository): Response
    {
        $form = $this->createForm(PaquetType::class, $paquet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paquetRepository->save($paquet, true);

            return $this->redirectToRoute('app_paquet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paquet/edit.html.twig', [
            'paquet' => $paquet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paquet_delete', methods: ['POST'])]
    public function delete(Request $request, Paquet $paquet, PaquetRepository $paquetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paquet->getId(), $request->request->get('_token'))) {
            $paquetRepository->remove($paquet, true);
        }

        return $this->redirectToRoute('app_paquet_index', [], Response::HTTP_SEE_OTHER);
    }
}
