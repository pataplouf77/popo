<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Contact;
use App\Form\ProductType;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class AdminProductController extends AbstractController
{
	/**
     * @var ProductRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * AdminProductController constructor.
     * @param ProductRepository $repository
     * @param ObjectManager $em
     */
    public function __construct(ProductRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/product", name="admin.product.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $products = $this->repository->findAll();
        return $this->render('admin/product/index.html.twig', compact('products'));
    }
	/**
     * @Route("/admin/product/contacter", name="admin.product.contacter")
	 * @param Request $request
     * @param ContactNotification $notification
     * @return Response
     */
	public function contacter(Request $request, ContactNotification $notification): Response 
    {
        
		$contact = new Contact();
		$form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
			return $this->redirectToRoute('admin.product.contacter');
		        }
		
		return $this->render('admin/product/contact.html.twig', [
						     'form'     => $form->createView()
        ]);
	        
    }
	
	//
	/**
     * @Route("/admin/product/create", name="admin.product.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt(new \DateTime('now'));
            $this->em->persist($product);
            $this->em->flush();
            $this->addFlash('success', 'Bien créé avec succès');
            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'form'     => $form->createView()
        ]);
    }
	//
	/**
     * @Route("/admin/product/{id}", name="admin.product.edit", methods="GET|POST")
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit(Product $product, Request $request)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt(new \DateTime('now'));
			//test de recuperer le nom du fichier a fin de valider une modif
			$product->setFilename($form->get('filename')->getData());
            $this->em->persist($product);
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form'     => $form->createView()
        ]);
    }
	//
	
	//
	/**
     * @Route("/admin/product/{id}", name="admin.product.delete", methods="DELETE")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Product $product, Request $request) {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->get('_token'))) {
            $this->em->remove($product);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('admin.product.index');
    }
}
