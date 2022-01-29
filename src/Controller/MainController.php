<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /** @var MailerInterface */
    private MailerInterface $mailer;

    /** @var string */
    private string $senderAddress;

    /** @var string */
    private string $contactEmailAddress;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer, string $senderAddress, string $contactEmailAddress)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->senderAddress = $senderAddress;
        $this->contactEmailAddress = $contactEmailAddress;
    }

    /**
     * @Route("/", name="main_homepage")
     */
    public function homepage(): Response
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/presentation", name="main_presentation")
     */
    public function presentation(): Response
    {
        return $this->render('main/presentation.html.twig');
    }

    /**
     * @Route("/contact", name="main_contact")
     */
    public function contact(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->subject('Une demande de contact a eu lieu')
                ->to($this->contactEmailAddress)
                ->from($this->senderAddress)
                ->html($this->renderView('email/contact.html.twig', [
                    'contact' => $contact
                ]));

            $this->mailer->send($email);

            $this->em->persist($contact);
            $this->em->flush();

            $this->addFlash('success', 'Votre demande contact a été prise en compte !');

            return $this->redirectToRoute('main_contact');
        }

        return $this->render('main/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
