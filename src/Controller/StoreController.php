<?php

namespace App\Controller;

use App\Entity\Store\Comment;
use App\Form\CommentType;
use App\Repository\Store\BrandRepository;
use App\Repository\Store\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/store", name="store_")
 */
class StoreController extends AbstractController
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /** @var ProductRepository */
    private ProductRepository $productRepository;

    /** @var BrandRepository */
    private BrandRepository $brandRepository;

    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository, BrandRepository $brandRepository)
    {
        $this->em = $em;
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
    }
    /**
     * @Route("", name="list")
     */
    public function list(): Response
    {
        return $this->render('store/list.html.twig', [
            'products' => $this->productRepository->findList(),
            'brands' => $this->brandRepository->findAll(),
        ]);
    }

    /**
     * @Route("/brand/{id}", name="list_by_brand", requirements={"id" = "\d+"})
     */
    public function listByBrand(int $id): Response
    {
        $brand = $this->brandRepository->find($id);
        if (!$brand) {
            throw new NotFoundHttpException();
        }

        return $this->render('store/list.html.twig', [
            'products' => $this->productRepository->findByBrand($brand),
            'brands' => $brand,
        ]);
    }

    /**
     * @Route("/product/{id}/details/{slug}", name="product", requirements={"id" = "\d+"})
     */
    public function product(Request $request, int $id, string $slug): Response
    {
        $product = $this->productRepository->findOneWithDetails($id);
        if (null === $product) {
            throw new NotFoundHttpException();
        }

        if ($product->getSlug() !== $slug) {
            return $this->redirectToRoute('store_product', [
                'id' => $id,
                'slug' => $product->getSlug(),
            ], Response::HTTP_MOVED_PERMANENTLY);
        }

        $comment = (new Comment())->setProduct($product);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($comment);
            $this->em->flush();

            $this->addFlash('success', 'Merci pour votre commentaire');

            return $this->redirectToRoute('store_product', [
                'id' => $product->getId(),
                'slug' => $product->getSlug(),

            ]);
        }

        return $this->render('store/product.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'brands'=> $this->brandRepository->findAll(),
        ]);
    }

    public function listBrands(?int $currentBrandId = null): Response
    {
        return $this->render('store/_list_brands.html.twig', [
            'brands' => $this->brandRepository->findBy([], ['name' => 'ASC']),
            'currentBrandId' => $currentBrandId,
        ]);
    }
}
