<?php

namespace App\Controller;

use App\Entity\AchatPacks;
use App\Entity\Paquet;
use App\Form\AchatPacksType;
use App\Repository\AchatPacksRepository;
use App\Repository\PaquetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use symfony\component\Serializer\Normalizer\NormalizableInterface;
use symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Serializer\SerializerInterface;

#[Route('/achat/packs')]
class AchatPacksController extends AbstractController
{
    #[Route('/', name: 'app_achat_packs_index', methods: ['GET'])]
    public function index(AchatPacksRepository $achatPacksRepository): Response
    {
        return $this->render('achat_packs/index.html.twig', [
            'achat_packs' => $achatPacksRepository->findAll(),
        ]);
    }



    #[Route("/Allachats", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getachat(AchatPacksRepository $repo, SerializerInterface $serializer)
    {
        $achatPacks = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
        // $studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "students"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
        // $json = json_encode($studentsNormalises);

        $json = $serializer->serialize($achatPacks, 'json', ['groups' => "achat"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }
    #[Route("/Allachats/{id}", name: "achat")]
    public function achatId($id, NormalizerInterface $normalizer, AchatPacksRepository $repo)
    {
        $achatPack = $repo->find($id);
        $achatPackNormalises = $normalizer->normalize($achatPack, 'json', ['groups' => "achat"]);
        return new Response(json_encode($achatPackNormalises));
    }
    #[Route("/addachatJSON/new", name: "addachatJSON")]
    public function addachatJSON(Request $req,   NormalizerInterface $Normalizer)
    {    

        $em = $this->getDoctrine()->getManager();
        $achatPack = new AchatPacks();
        $achatPack->setNamePaquet($req->get('nom'));
        $achatPack->setNumcarte($req->get('numero'));
        $achatPack->setDate($req->get('date'));
        
        $em->persist($achatPack);
        $em->flush();

        $jsonContent = $Normalizer->normalize($achatPack, 'json', ['groups' => "achat"]);
        return new Response(json_encode($jsonContent));
        
    }
    
    #[Route("/updateachatJSON/{id}", name: "updateachatJSON")]
    public function updateachatJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $achatPack  = $em->getRepository(chatPacks::class)->find($id);
        $achatPack = new AchatPacks();
        $achatPack->setNamePaquet($req->get('nom'));
        $achatPack->setNumcarte($req->get('numero'));
        $achatPack->setDate($req->get('date'));

        $em->flush();

        $jsonContent = $Normalizer->normalize( $achatPack, 'json', ['groups' => "achat"]);
        return new Response("achat updated successfully " . json_encode($jsonContent));
    }




    #[Route("/deleteachatJSON/{id}", name: "deleteachatJSON")]
    public function deleteDemandJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $achatPack = $em->getRepository(CharityDemand::class)->find($id);
        $em->remove($achatPack);
        $em->flush();
        $jsonContent = $Normalizer->normalize($achatPack, 'json', ['groups' => "achat"]);
        return new Response("achat deleted successfully " . json_encode($jsonContent));



    }




    #[Route('/new', name: 'app_achat_packs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AchatPacksRepository $achatPacksRepository): Response
    {
        $achatPack = new AchatPacks();
        $form = $this->createForm(AchatPacksType::class, $achatPack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $achatPacksRepository->save($achatPack, true);

            return $this->redirectToRoute('app_achat_packs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achat_packs/new.html.twig', [
            'achat_pack' => $achatPack,
            'form' => $form,
        ]);
    }
    #[Route('/new/{id}', name: 'app_achat_packs_id', methods: ['GET', 'POST'])]
    public function achat(Request $request, AchatPacksRepository $achatPacksRepository , Paquet $paquet): Response
    {
        $achatPack = new AchatPacks();
        $form = $this->createForm(AchatPacksType::class, $achatPack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $achatPacksRepository->save($achatPack, true);

            return $this->redirectToRoute('app_achat_packs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achat_packs/new.html.twig', [
            'achat_pack' => $achatPack,
            'form' => $form,
            'paquet'=>$paquet,
        ]);
    }

    #[Route('/{id}', name: 'app_achat_packs_show', methods: ['GET'])]
    public function show(AchatPacks $achatPack): Response
    {
        return $this->render('achat_packs/show.html.twig', [
            'achat_pack' => $achatPack,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_achat_packs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AchatPacks $achatPack, AchatPacksRepository $achatPacksRepository): Response
    {
        $form = $this->createForm(AchatPacksType::class, $achatPack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $achatPacksRepository->save($achatPack, true);

            return $this->redirectToRoute('app_achat_packs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achat_packs/edit.html.twig', [
            'achat_pack' => $achatPack,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_achat_packs_delete', methods: ['POST'])]
    public function delete(Request $request, AchatPacks $achatPack, AchatPacksRepository $achatPacksRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achatPack->getId(), $request->request->get('_token'))) {
            $achatPacksRepository->remove($achatPack, true);
        }

        return $this->redirectToRoute('app_achat_packs_index', [], Response::HTTP_SEE_OTHER);
    }
}
