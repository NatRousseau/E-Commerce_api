<?php

namespace App\Controller;

use App\Entity\OrderProduct;
use App\Entity\Orders;
use App\Entity\Product;
use App\Form\EditProfileType;
use App\Form\OrderType;
use App\Repository\OrderProductRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use phpDocumentor\Reflection\Types\Array_;
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
    /**
     * @var OrderProductRepository
     */
    private $orderProductRepository;

    public function __construct(OrdersRepository $repository, EntityManagerInterface $em, SessionInterface $session, ProductRepository $productRepository, OrderProductRepository $orderProductRepository)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->orderProductRepository = $orderProductRepository;
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

    /**
     * @Route("/profil/commandes", name="order.view")
     * @return Response
     */
    public function view()
    {
        $user = $this->getUser();

        $orders = $this->repository->findByUser($user);
//        foreach ($orders as $id => $orderId) {
//            $test = null;
//
//            $orderid = $orders[$id]->getId();

        return $this->render('user/view.html.twig', [
            'orders' => $orders,
//                'tests' => $test
        ]);


//        }
    }


    /**
     * @Route("/profil/commandes/detail{slug}-{id}", name="order.detail")
     * @param Orders $orders
     * @param int $slug
     * @return Response
     */
    public function show(Orders $orders, int $slug): Response
    {
        if ($orders->getId() !== $slug) {
            return $this->redirectToRoute('order.detail', [
                'id' => $orders->getId(),
                'slug' => $orders->getSlug()
            ]);
        }

        $orderid = $orders->getId();
        $order_products = $this->orderProductRepository->findByOrder($orderid);
        $productsList = [];

        foreach ($order_products as $key => $value) {
            $productId = $order_products[$key]->getProductId();
            $productsList[] = $this->productRepository->find($productId);
        }

        return $this->render('user/detail.html.twig', [
            'order' => $orders,
            'orderProducts' => $order_products,
            'products' => $productsList,
            'current_menu' => 'properties'
        ]);
    }

    /**
     * @Route("/profil/commandes/detail/pdf-{id}", name="order.pdf")
     * @param Orders $orders
//     * @param int $slug
     * @return Response
     */
    public function pdf(Orders $orders) : Response
    {
        $orderid = $orders->getId();
        $order_products = $this->orderProductRepository->findByOrder($orderid);
        $productsList = [];

        foreach ($order_products as $key => $value) {
            $productId = $order_products[$key]->getProductId();
            $productsList[] = $this->productRepository->find($productId);
        }

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('user/mypdf.html.twig', [
            'title' => "Welcome to our PDF Test",
            'order' => $orders,
            'orderProducts' => $order_products,
            'products' => $productsList,
            'current_menu' => 'properties'
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("facture.pdf", [
            "Attachment" => true
        ]);

        return $this->render('user/detail.html.twig', [
            'order' => $orders,
            'orderProducts' => $order_products,
            'products' => $productsList,
            'current_menu' => 'properties'
        ]);
    }
}