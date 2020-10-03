<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Form\EditProfileType;
use App\Form\OrderType;
use App\Repository\OrdersRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function __construct(OrdersRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
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
        $form = $this->createForm(EditProfileType::class, $user);
        $orderform = $this->createForm(OrderType::class, $order);
        $orderform->handleRequest($request);

        if ($orderform->isSubmitted() && $orderform->isValid()) {
            $this->em->persist($order);
            $this->em->flush();
            $this->addFlash('success', 'La commande à été créé avec succés');
            return $this->redirectToRoute('profil.index');
        }

        return $this->render('order/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'form' => $form->createView(),
            'orderForm' => $orderform->createView()
        ]);
    }
}
