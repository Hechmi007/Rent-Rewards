<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\NamedAddress;
use Symfony\Component\Mime\TemplatedEmail;

class MailerController extends AbstractController
{

    public function sendEmail(MailerInterface $mailer)
    {
        // Create and send email with Twig template
        $email = (new TemplatedEmail())
            ->from(new NamedAddress('sender@example.com', 'Me'))
            ->to(new NamedAddress('amine.azri@esprit', 'John'))
            ->subject('Welcome!')
            ->context([
                'datetime' => new \DateTime('now'),
            ])
            ->htmlTemplate('email/sent.html.twig');

        $mailer->send($email);

        // Return a response
        return $this->render('email/sent.html.twig');
    }
}
