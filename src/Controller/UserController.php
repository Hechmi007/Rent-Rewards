<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/user')]
class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

/*     #[Route('/{page?1}/{nbre?5}', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository,$nbre,$page): Response
    {
        $nbUser = $userRepository -> count([]);
        $nbrePage = ceil($nbUser / $nbre);
        return $this->render('user/index.html.twig', ['isPaginated'=>true,
            'users' => $userRepository->findBy([],[],$nbre,($page -1) * $nbre),
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre,
        ]);
    }
 */
<<<<<<< Updated upstream
 #[Route('showall/{page?1}', name: 'app_user_index', methods: ['GET','POST'])]
public function index(UserRepository $userRepository,$page=1): Response
            {   
            $nbre=5;
            $nbrUser=$userRepository->count([]);
            $nbrPages=ceil($nbrUser/$nbre);
            $rolesString = 'a:1:{i:0;s:10:"ROLE_AGENT";} a:1:{i:0;s:14:"ROLE_LOCATAIRE";} a:1:{i:0;s:11:"ROLE_CLIENT";} a:1:{i:0;s:10:"ROLE_ADMIN";}';

            $rolesArray = array("ROLE_AGENT","ROLE_ADMIN","ROLE_CLIENT","ROLE_LOCATAIRE");
=======
/* #[Route('/', name: 'app_user_index', methods: ['GET'])]
public function index(UserRepository $userRepository): Response
            {   $rolesString = 'a:1:{i:0;s:10:"ROLE_AGENT";} a:1:{i:0;s:14:"ROLE_LOCATAIRE";} a:1:{i:0;s:11:"ROLE_CLIENT";}';

            $rolesArray = array("ROLE_AGENT","ROLE_ADMIN","ROLE_CLIENT");
>>>>>>> Stashed changes

            $cleanedRolesArray = array();

            foreach ($rolesArray as $role) {
                $cleanedRole = trim($role);
                $cleanedRolesArray[] = $cleanedRole;
            }
            foreach($cleanedRolesArray as $c){
                $nbr[]=$userRepository->countUsersByRole($c);
            }
<<<<<<< Updated upstream
            $roles= array("agent","admin","client","locataire");
    return $this->render('user/index.html.twig', [
        'isPaginated' => ($nbrUser<5?false:true),
        'nbrPages'=> ($nbrPages),
        'currentPage'=>($page),
        'users' => $userRepository->findBy([],[],$nbre,($page -1) * $nbre),
=======
            $roles= array("admin","locataire","client");
    return $this->render('user/index.html.twig', [
        'isPaginated' => false,
        'users' => $userRepository->findAll(),
>>>>>>> Stashed changes
        'Roles' => json_encode($roles),
        'nbr' =>json_encode($nbr)
    ]);
}
<<<<<<< Updated upstream
 
/* #[Route('/', name: 'app_user_index', methods: ['GET'])]
=======
 */
#[Route('/', name: 'app_user_index', methods: ['GET'])]
>>>>>>> Stashed changes
public function index(UserRepository $userRepository): Response
{
    return $this->render('user/index.html.twig', [
        'isPaginated' => false,
        'users' => $userRepository->findAll(),
    ]);
}

<<<<<<< Updated upstream
 */    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UserPasswordHasherInterface $UserPasswordHasher): Response
=======
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
>>>>>>> Stashed changes
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setPassword(
                $UserPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user,$plainpwd);
            $user->setPassword($encoded);
            
            $entityManager->persist($user);
            $entityManager->flush();            

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository, WalletRepository $walletRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $wallet = $user->getWallet();
            if ($wallet) {
                $walletRepository->remove($wallet, true);
            }
            $userRepository->remove($user, true);
        }
    
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    
}

 
