<?php

namespace App\Controller;

//use App\Form\UserType;
use App\Entity\User;
use App\Events;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
//
use App\Form\RegistrationFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="user_registration")
     */
	public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher) 
        {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
      
	  $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
			// Par defaut l'utilisateur aura toujours le rôle ROLE_ADMIN
            //$user->setRoles(['ROLE_ADMIN']);
			//$role = array($form->get('roles')->getData());
            //$user->setRoles(array('role' => $role));             
            //$user->setRoles($role);
            // On enregistre l'utilisateur dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //On déclenche l'event
            $event = new GenericEvent($user);
            $eventDispatcher->dispatch(Events::USER_REGISTERED, $event);

            return $this->redirectToRoute('security_login');
        }

        return $this->render('registration/register.html.twig', [ 'registrationForm' => $form->createView(),
        ]);
    }
}
