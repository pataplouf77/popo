<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CoachController extends AbstractController
{
    /**
     * @Route("/coach", name="coach")
     */
    public function index()
    {
        return $this->render('coach/index.html.twig', [
            'controller_name' => 'CoachController',
        ]);
    }
}
