<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(ProductRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */

    public function index(): Response
    {
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties'
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show",requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */

    public function show(Product $product, string $slug): Response
    {
        if ($product->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $product->getId(),
                'slug' => $product->getSlug()
            ], 301);
        }
        return $this->render('Property/show.html.twig', [
            'product' => $product,
            'current_menu' => 'properties'
        ]);
    }
}
