<?php

namespace App\Controller;

use App\Repository\Store\BrandRepository;
use App\Repository\Store\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/store", name="store_")
 */
class StoreController extends AbstractController
{
    /** @var ProductRepository */
    private ProductRepository $productRepository;

    /** @var BrandRepository */
    private BrandRepository $brandRepository;

    public function __construct(ProductRepository $productRepository, BrandRepository $brandRepository)
    {
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
    }
    /**
     * @Route("", name="list")
     */
    public function list(): Response
    {
        return $this->render('store/list.html.twig', [
            'products' => $this->productRepository->findAll(),
            'brands' => $this->brandRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    /**
     * @Route("/product/{id}/details/{slug}", name="product", requirements={"id" = "\d+"})
     */
    public function product(int $id, string $slug): Response
    {
        $product = $this->productRepository->find($id);
        if (null === $product) {
            throw new NotFoundHttpException();
        }

        if ($product->getSlug() !== $slug) {
            return $this->redirectToRoute('store_product', [
                'id' => $id,
                'slug' => $product->getSlug(),
            ], Response::HTTP_MOVED_PERMANENTLY);
        }

        return $this->render('store/product.html.twig', [
            'product' => $product,
            'brands' => $this->brandRepository->findBy([], ['name' => 'ASC']),
        ]);
    }
}
