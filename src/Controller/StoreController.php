<?php

  namespace App\Controller;


  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;


  class StoreController extends AbstractController {

    #[Route('/store/product/{id}/details/{slug}', name: 'store_show_product' , requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showProduct(int $id, string $slug, Request $request,): Response {
      return $this->render('store/index.html.twig', [
        'id' => $id,
        'slug' => $slug,
        'ip' => $request->server->get('REMOTE_ADDR'),
        'uri' => $request->server->get('REQUEST_URI')
      ]);
    }


  }
