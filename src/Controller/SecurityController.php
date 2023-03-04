<?php

namespace App\Controller;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Form\ResetPasswordRequestType;
use App\Form\ResetPasswordType;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SecurityController extends AbstractController
{
    private $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }
    
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $req, UserRepository $rep): Response
    {   
        $session=$req->getSession();
        
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $this->authenticationUtils->getLastUsername();
        $user=$rep->findbyname($lastUsername);
        $session->set('user',$user);
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername,'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

/**
 * @Route("/forgot-password", name="app_forgot_password")
 */
public function forgotPassword(
    Request $request,
    UserRepository $userRepository,
    TokenGeneratorInterface $tokenGenerator,
    Swift_Mailer $mailer
) {
    $form = $this->createForm(ResetPasswordRequestType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if ($user) {
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'An error occurred while trying to reset your password. Please try again later.'
                );

                return $this->redirectToRoute('app_forgot_password');
            }

            $message = (new Swift_Message('Password Reset Request'))
                ->setFrom('Daliguidara44@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'security/reset_password_email.html.twig',
                        ['token' => $token]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash(
                'success',
                'A password reset link has been sent to your email address.'
            );

            $success = true;
        } else {
            $this->addFlash(
                'error',
                'There is no account associated with that email address.'
            );

            $success = false;
        }
    } else {
        $success = null;
    }

    return $this->render('security/forgot_password.html.twig', [
        'form' => $form->createView(),
        'success' => $success,
        'error' => $this->authenticationUtils->getLastAuthenticationError(),
    ]);
}

/**
 * @Route("/reset-password/{token}", name="app_reset_password")
 */
public function resetPassword(
    Request $request,
    UserPasswordEncoderInterface $passwordEncoder,
    string $token
) {
    $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['resetToken' => $token]);

    if (!$user) {
        $this->addFlash(
            'errors',
            'Invalid or expired reset token.'
        );

        return $this->redirectToRoute('app_login');
    }

    $form = $this->createForm(ResetPasswordType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        $user->setPassword($passwordEncoder->encodePassword($user, $data['password']));
        $user->setResetToken(null);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash(
            'successx',
            'Your password has been successfully reset.'
        );

        return $this->redirectToRoute('app_login');
    }

    return $this->render('security/reset_password.html.twig', [
        'form' => $form->createView(),
        'error' => $this->authenticationUtils->getLastAuthenticationError(),
    ]);
}
public function sendPasswordResetEmail(MailerInterface $mailer, Request $request, UserRepository $userRepository): Response
{
    // ...
    
    $email = (new TemplatedEmail())
        ->from('Daliguidara44@gmail.com')
        ->to($user->getEmail())
        ->subject('Reset your password')
        ->htmlTemplate('security/reset_password_email.html.twig')
        ->context([
            'resetToken' => $resetToken,
            'user' => $user,
            'reset_url' => $resetUrl,
        ]);

    // ...
}

}