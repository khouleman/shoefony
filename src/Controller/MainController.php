<?php

  namespace App\Controller;

  use App\Entity\Contact;
  use App\Form\ContactType;
  use App\Mailer\ContactMailer;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\Routing\Annotation\Route;

  class MainController extends AbstractController {

    #[Route('/contact', name: 'main_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request): Response {

      // Creation de notre entite et du formulaire base dessus
      $contact = new Contact();
      $form = $this->createForm(ContactType::class, $contact);

      // Demande au formulaire d'interpreter la Request
      $form->handleRequest($request);

      // Dans le cas de la soumission d'un formulaire valide
      if ($form->isSubmitted() && $form->isValid()) {
        $this->addFlash('success', 'Merci, votre message a bien été pris en compte !');

        // Actions a effectuer apres envoi du formulaire
        return $this->redirectToRoute('main_contact');
      }

      return $this->render('main/contact.html.twig', [
        'form' => $form->createView(),
      ]);

    }

    #[Route('/', name: 'main_homepage', methods: ['GET'])]
    public function homepage(): Response {
      return $this->render('main/homepage.html.twig', [
        'controller_name' => 'MainController',
      ]);
    }

    #[Route('/presentation', name: 'main_presentation', methods: ['GET'])]
    public function listProducts(): Response {
      return $this->render('main/presentation.html.twig');
    }



    #[Route('/produits-liste', name: 'store_product_list')]
    public function productList(): Response {
      return $this->render('main/productList.html.twig');
    }

  }


