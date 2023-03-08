<?php

namespace App\Controller;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Gregwar\CaptchaBundle\Validator\Constraints\ValidCaptcha;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager, Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
    
// generate and set confirmation token
$user->setConfirmationToken(md5(random_bytes(60)));

$entityManager->persist($user);
$entityManager->flush();

// send confirmation email
// create Swift_Message instance
$token = $user->getConfirmationToken();
$message = (new Swift_Message('Confirm your email'))
    ->setFrom('Daliguidara@gmail.com')
    ->setTo($user->getEmail())
    ->setBody(
        $this->renderView(
            'emails/confirm_email.html.twig',
            ['token' => $token, 'url' => $this->generateUrl('confirm_email', [
                'token' => $token,
                
            ], UrlGeneratorInterface::ABSOLUTE_URL)]
        ),
        'text/html'
    );

// send message using Swift_Mailer
$mailer->send($message);    
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
 * @Route("/confirm-email/{token}", name="confirm_email")
 */
public function confirmEmail($token)
{
    $entityManager = $this->getDoctrine()->getManager();

    // Find the user by confirmation token
    $user = $entityManager->getRepository(User::class)->findOneBy([
        'confirmationToken' => $token,
    ]);

    if (!$user) {
        throw $this->createNotFoundException('The user with the confirmation token "'.$token.'" does not exist.');
    }

    // Mark the user's email as confirmed
    $user->setEmailVerified(true);
    $user->setConfirmationToken(null);
    $entityManager->flush();

    // Redirect to the login page or a "thank you" page
    return $this->redirectToRoute('login');
}

}
