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
    private $em;

    /** @var MailerInterface */
    private $mailer;

    /** @var string */
    private $senderAddress;

    /** @var string */
    private $contactAddress;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer, string $senderAddress, string $contactAddress)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->senderAddress = $senderAddress;
        $this->contactAddress = $contactAddress;
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
                ->to($this->contactAddress)
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
