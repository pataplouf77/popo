<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
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
       * ProductController constructor.
       * @param ProductRepository $repository
       * @param ObjectManager $em
       */
      public function __construct(ProductRepository $repository, ObjectManager $em)
      {
          $this->repository = $repository;
          $this->em = $em;
      }
	/**
       * @Route("/product", name="product.index")
       * @param Request $request
       * @return Response
       */
    public function index()
    {
        $products = $this->repository->findAll();
        return $this->render('product/index.html.twig', compact('products'));
    } 	
	
    /**
     * @Route("/product/test", name="product.test")
     */
    public function product()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
	
	/**
     * @Route("/product1", name="product1")
     */
    public function product1()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = new Product();
		    $toto = "juiki";
		    $titi = "fichier";
        $product->setName($toto);
        $product->setPrice(215);
        $product->setFilename($titi);
		$product->setUpdatedAt(new \DateTime('now'));
	      $entityManager->persist($product);
        $entityManager->flush();
		//
		return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController1',
        ]);
    }
	/**
     * @Route("/product2", defaults={"page": "1", "_format"="html"}, methods={"GET"}, name="product2_show")
     * @Route("/rss.xml", defaults={"page": "1", "_format"="xml"}, methods={"GET"}, name="blog_rss")
     * @Route("/page/{page}", defaults={"_format"="html"}, requirements={"page": "[1-9]\d*"}, methods={"GET"}, name="blog_kiki_paginated")
     * @Cache(smaxage="10")
     *
     * NOTE: For standard formats, Symfony will also automatically choose the best
     * Content-Type header for the response.
     * See https://symfony.com/doc/current/quick_tour/the_controller.html#using-formats
     */ 
    public function product2(int $page, string $_format, ProductRepository $products): Response
    {
		//ici j ai remplacer index par kiki pour faire un test donc function index....
		$latestProducts = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('product/index.'.$_format.'.twig', ['products' => $latestProducts]);

    }
	
}
