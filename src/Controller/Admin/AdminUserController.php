<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\RegistrationFormType;
use App\Form\ChangePasswordType;
use App\Form\ContactType;
use App\Repository\UserRepository;
use App\Notification\ContactNotification;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $user;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * AdminPropertyController constructor.
     * @param PropertyRepository $repository
     * @param ObjectManager $em
     */
    public function __construct(UserRepository $user, ObjectManager $em)
    {
        $this->repository = $user;
        $this->em = $em;
    }

    /**
     * @Route("/admin/user", name="admin.user.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $users = $this->repository->findAll();
        return $this->render('admin/user/index.html.twig', compact('users'));
    }

	/**
     * @Route("/admin/user/create", name="admin.user.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request , UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			//
            //$password = $passwordEncoder->encodePassword($user, $user->getPassword());
            //$user->setPassword($password);

            // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
			// Par defaut l'utilisateur aura toujours le rôle ROLE_ADMIN
            //$user->setRoles(['ROLE_USER']);
			$password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
			//
			$entityManager = $this->getDoctrine()->getManager();
			$this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'Bien créé avec succès');
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form'     => $form->createView()
        ]);
    }
	/**
     * @Route("/admin/user/contacter", name="admin.user.contacter")
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
			return $this->redirectToRoute('admin.user.contacter');
		        }
		
		return $this->render('admin/user/contact.html.twig', [
						     'form'     => $form->createView()
        ]);
	        
    }
	/**
     * @Route("/admin/user/{id}", name="admin.user.edit", methods="GET|POST")
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit(User $user, Request $request)
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$property->setUpdatedat(new \DateTime('now'));
			//$user->setRoles(['ROLE_USER']);
			$user->setUpdatedAt(new \DateTime('now'));
			$user->setFilename($form->get('filename')->getData());
			$user->setUpdatedAt2(new \DateTime('now'));
			$user->setFilename2($form->get('filename2')->getData());
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form'     => $form->createView()
        ]);
    }
	/**
     * @Route("/admin/user/{id}", name="admin.user.delete", methods="DELETE")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(User $user, Request $request) {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('admin.user.index');
    }
	
	 
	//
	/**
     * @Route("/change-password", methods={"GET", "POST"}, name="user_change_password")
	 * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
		
        $form = $this->createForm(ChangePasswordType::class , $user );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			$user->setPlainPassword($form->get('password')->getData()) ;
			
            $user->setPassword($encoder->encodePassword($user, $form->get('password')->getData()));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('admin/user/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
	
 }
