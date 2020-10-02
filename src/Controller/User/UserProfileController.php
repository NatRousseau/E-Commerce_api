<?php

namespace App\Controller\User;

use App\Entity\Product;
use App\Entity\User;
use App\Form\EditProfileType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
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
     * @Route ("/profil", name="profil.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        return $this->render('user/index.html.twig');
    }


    /**
     * @Route ("/profil/modifier", name="profil.edit", methods="GET|POST")
     * @param Request $request
     */

    public function edit(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('profil.index');
        }

        return $this->render('user/editprofile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}