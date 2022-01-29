<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Mailer\ContactMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, ContactMailer $contactMailer)
    {
        $this->em = $em;
        $this->contactMailer = $contactMailer;
    }

    #[Route('/contact', name: 'main_contact')]
    public function mainContact(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Merci, votre message a été pris en compte !');
            $this->em->persist($contact);
            $this->em->flush();
            return $this->redirectToRoute('main_contact');
        }

        return $this->render('contact/mainContact.html.twig' , ['form' => $form->createView()]);
    }
}
