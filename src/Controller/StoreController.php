<?php

  namespace App\Controller;


  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;


  class StoreController extends AbstractController {

    #[Route('/produit-details/{id}', name: 'store_show_product' , requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showProduct(int $id): Response {
      return $this->render('main/productDetails.html.twig', [
        'id' => $id
      ]);
    }
  }
