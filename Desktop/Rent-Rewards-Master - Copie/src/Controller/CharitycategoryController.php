<?php

namespace App\Controller;

use App\Entity\Charitycategory;
use App\Form\CharitycategoryType;
use App\Repository\CharitycategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

use symfony\component\Serializer\Normalizer\NormalizableInterface;
use symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/charitycategory')]
class CharitycategoryController extends AbstractController
{
    #[Route('/', name: 'app_charitycategory_index', methods: ['GET'])]
    public function index(CharitycategoryRepository $charitycategoryRepository ,   PaginatorInterface  $paginator, Request $request): Response
    {  $entityManager = $this->getDoctrine()->getManager();
        $charitycategoryRepository = $entityManager->getRepository(Donation::class);

        $queryBuilder = $charitycategoryRepository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5 // number of items per page
        );

        return $this->render('donation/index.html.twig', [
            'pagination' => $pagination,
        ]);











        return $this->render('charitycategory/index.html.twig', [
            'charitycategories' => $charitycategoryRepository->findAll(),
        ]);
    }


    #[Route("/AllCategorie", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getCategory(CharitycategoryRepository $repo, SerializerInterface $serializer)
    {
        $charitycategories = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

        $json = $serializer->serialize($charitycategories, 'json', ['groups' => "charitycategory"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }


    #[Route("/Allcategorie/{id}", name: "categorie")]
    public function categorysId($id, NormalizerInterface $normalizer, CharitycategoryRepository $repo)
    {
        $charitycategory = $repo->find($id);
        $categorysNormalises = $normalizer->normalize($charitycategory, 'json', ['groups' => "charitycategory"]);
        return new Response(json_encode($categorysNormalises));
    }
    #[Route("/addcategorieJSON/new", name: "addcategorieJSON")]
    public function addSDemandJSON(Request $req,   NormalizerInterface $Normalizer)
    {    

        $em = $this->getDoctrine()->getManager();
        $charitycategory = new Charitycategory();
        $charitycategory->setType($req->get('Type'));
        $charitycategory->setDateCharity(new \DateTime($req->get('date')));
       
        $em->persist( $charitycategory);
        $em->flush();

        $jsonContent = $Normalizer->normalize( $charitycategory, 'json', ['groups' => "charitycategory"]);
        return new Response(json_encode($jsonContent));
        //https://127.0.0.1:8000/charity/demand/addDemandsJSON/new?Title=wael~&receiver=wael&pointsdemanded=11111&datedemande=2023-11-12&file=ILLUSTRATION2-63fe33d4e13a4.jpg
    }
    
    #[Route("/updatecategorieJSON/{id}", name: "updateDemandJSON")]
    public function updateDemandJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $charitycategory = $em->getRepository(Charitycategory::class)->find($id);
        $charitycategory = new Charitycategory();
        $charitycategory->setType($req->get('Type'));
        

        $em->flush();

        $jsonContent = $Normalizer->normalize( $charitycategory, 'json', ['groups' => "charitycategory"]);
        return new Response("Demand updated successfully " . json_encode($jsonContent));
    }




    #[Route("/deletecategorieJSON/{id}", name: "deleteDemandJSON")]
    public function deleteDemandJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $charitycategory = $em->getRepository(CharityDemand::class)->find($id);
        $em->remove($charitycategory);
        $em->flush();
        $jsonContent = $Normalizer->normalize($charitycategory, 'json', ['groups' => "charitycategory"]);
        return new Response("Student deleted successfully " . json_encode($jsonContent));
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
