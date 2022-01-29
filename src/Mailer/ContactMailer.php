<?php

  namespace App\Mailer;

  use App\Entity\Contact;
  use Doctrine\ORM\EntityManagerInterface;
  use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
  use Symfony\Component\Mailer\MailerInterface;
  use Symfony\Component\Mime\Email;
  use Twig\Environment;

  final class ContactMailer {

    private MailerInterface $mailer;
    private Environment $twig;
    private string $contactEmailAddress;

    public function __construct(MailerInterface $mailer, Environment $twig, string $contactEmailAddress) {
      $this->mailer = $mailer;
      $this->twig = $twig;
      $this->contactEmailAddress = $contactEmailAddress;
    }

    public function send(Contact $contact) {
      $twig = '';
      $email = (new Email())
        ->from($contact->getEmail())
        ->to($this->contactEmailAddress)
        ->subject('Un message de contact sur Shoefony')
        ->html($this->twig->render('contact/mainContact.html.twig', ['contact' => $contact]));
      try {
        $this->mailer->send($email);
      } catch (TransportExceptionInterface){

      }
    }

  }
