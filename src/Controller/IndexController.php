<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\EventDispatcher\EventDispatcherInterface;
//use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Response;


class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }
	/**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('admin/index.html.twig');
		//return $this->redirect('/fabien/index.php');
		
    }
}
