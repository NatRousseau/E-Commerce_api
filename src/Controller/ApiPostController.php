<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;

class ApiPostController extends AbstractController
{
    /**
     * @Route("/api/post", name="api_post_index", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param NormalizableInterface $normalizer
     * @return Response
     */
    public function index(ProductRepository $productRepository, NormalizableInterface $normalizable)
    {
        $products = $productRepository->findAll();

        $productsNormalises = $normalizable->normalize($products, null, ['groups' => 'products:read']);

        dd($productsNormalises);

        return $this->render('api_post/index.html.twig', [
            'controller_name' => 'ApiPostController',
        ]);
    }
}
