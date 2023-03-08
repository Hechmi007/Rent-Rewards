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
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Authentication\AccessToken;
use Facebook\Authentication\OAuth2Client;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Support\Facades\Env;

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
        if ($user && $user->getBanned()) {
            $this->addFlash('error', 'Your account has been banned. Please contact the administrator for more information.');
            return $this->redirectToRoute('app_login');
        }
        $facebookLoginEnabled = true;
        $session->set('user',$user);
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername,'error' => $error,'facebook_login_enabled' => $facebookLoginEnabled,]);
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
                        [ 'username' => $user->getUsername(),
                            'token' => $token]
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
/**
 * @Route("/login/facebook", name="facebook_login")
 */
public function facebookLogin(Request $request, Security $security)
{
    $fb = new \Facebook\Facebook([
        'app_id' => $this->getParameter('facebook_app_id'),
        'app_secret' => $this->getParameter('facebook_app_secret'),
        'default_graph_version' => 'v3.2',
    ]);

    $helper = $fb->getRedirectLoginHelper();

    $redirectUrl = $request->getUriForPath('/login/facebook-check');

    $permissions = ['email'];

    $loginUrl = $helper->getLoginUrl($redirectUrl, $permissions);

    return new RedirectResponse($loginUrl);
}
/**
 * @Route("/login/facebook-check", name="facebook_login_check")
 */
public function facebookLoginCheck(Request $request, Security $security)
{
    $fb = new \Facebook\Facebook([
        'app_id' => $this->getParameter('facebook_app_id'),
        'app_secret' => $this->getParameter('facebook_app_secret'),
        'default_graph_version' => 'v3.2',
    ]);

    $helper = $fb->getRedirectLoginHelper();

    try {
        $accessToken = $helper->getAccessToken();
    } catch (FacebookResponseException $e) {
        // Handle exception
    } catch (FacebookSDKException $e) {
        // Handle exception
    }

    if (!isset($accessToken)) {
        return $this->redirectToRoute('login');
    }

    $oAuth2Client = $fb->getOAuth2Client();
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);

    $tokenMetadata->validateExpiration();

    if (!$accessToken->isLongLived()) {
        try {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (FacebookSDKException $e) {
            // Handle exception
        }
    }

    $response = $fb->get('/me?fields=id,name,email', $accessToken);

    $user = $response->getGraphUser();

    // Check if the user already exists in the database
    $existingUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

    if (!$existingUser) {
        // User doesn't exist in the database, create a new user with the ROLE_CLIENT role
        $newUser = new User();
        $newUser->setEmail($user->getEmail());
        $newUser->setFacebookId($user->getId());
        $newUser->setRoles(['ROLE_CLIENT']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newUser);
        $entityManager->flush();
        $existingUser = $newUser;
    }

    // Log in the user and redirect to the home page
    $token = new UsernamePasswordToken($existingUser, null, 'main', $existingUser->getRoles());
    $security->getTokenStorage()->setToken($token);
    $this->getDoctrine()->getManager()->flush();
    return $this->redirectToRoute('home');
}

}