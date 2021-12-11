<?php

  namespace App\Controller;

  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;

  class MainController extends AbstractController {

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

    #[Route('/contact', name: 'main_contact')]
    public function contact(): Response {
      return $this->render('main/contact.html.twig');
    }

    #[Route('/produits-liste', name: 'store_product_list')]
    public function productList(): Response {
      return $this->render('main/productList.html.twig');
    }

  }
