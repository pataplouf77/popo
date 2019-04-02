<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/security_login", name="security_login")
     */
	public function security_login(AuthenticationUtils $authenticationUtils) 
    //public function security_login(AuthenticationUtils $helper): Response
    {
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();
        
		return $this->render('security/login.html.twig', [
            // dernier username saisi (si il y en a un)
            //'last_username' => $helper->getLastUsername(),
            // La derniere erreur de connexion (si il y en a une)
            //'error' => $helper->getLastAuthenticationError(),
			 'last_username' => $lastUsername,
			'error'         => $error,
        ]);
    }
	
	/**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
		return $this->render('security/logout.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }
	
   }
