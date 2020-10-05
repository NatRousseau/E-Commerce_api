<?php

namespace App\Controller;

use App\Entity\OrderProduct;
use App\Entity\Orders;
use App\Form\EditProfileType;
use App\Form\OrderType;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController

{
    /**
     * @var OrdersRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(OrdersRepository $repository, EntityManagerInterface $em, SessionInterface $session, ProductRepository $productRepository)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->session = $session;
        $this->productRepository = $productRepository;
    }


    /**
     * @Route("/order", name="order_index")
     * @param CartService $cartService
     * @param Request $request
     * @return Response
     */
    public function index(CartService $cartService, Request $request)
    {
        $user = $this->getUser();
        $order = new Orders();
        $panier = $this->session->get('panier', []);

        $order->setUserId($user->getId());
        $order->setBillingAdress($user->getBillingAdress());
        $order->setBillingCity($user->getBillingCity());
        $order->setBillingPostalCode($user->getBillingPostalCode());
        $order->setShippingAdress($user->getShippingAdress());
        $order->setShippingCity($user->getShippingCity());
        $order->setShippingPostalCode($user->getShippingPostalCode());
        $order->setPaiementStatus('En cours de paiement');

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($order);
            $this->em->flush();
            foreach ($panier as $id => $quantity) {
                $order_product = new OrderProduct();
                $order_product->setIdOrder($order->getId());
                $order_product->setProductId($id);
                $order_product->setProductQuantity($quantity);
                $this->em->persist($order_product);
                $this->em->flush();
            }
            $this->addFlash('success', 'La commande à été créé avec succés');
            return $this->redirectToRoute('profil.index');
        }

        return $this->render('order/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'form' => $form->createView()
        ]);
    }
}
