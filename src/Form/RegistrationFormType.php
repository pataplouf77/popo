<?php
// /src/Form/UserType.php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		//
        $builder
            ->add('fullName', TextType::class ,['label' => 'Nom de famille'] )
		    ->add('email', EmailType::class , ['label' => 'Email'] )            
            ->add('username', TextType::class)
			->add('plainPassword', TextType::class)
			->add('niveau', ChoiceType::class, [
								'choices'  => [
					'niveau0' => 'niveau0',			
					'niveau1' => 'niveau1',
					'niveau2' => 'niveau2',
					'niveau3' => 'niveau3',
					'niveau4' => 'niveau4',
					'E1' => 'E1',	'E2' => 'E2',	'E3' => 'E3',
					],	])
		->add('certif', TextType::class)
		
		->add('filename')
		->add('imageFile', FileType::class, ['label' => 'photo Ident' ,  'required' => false ] )
		->add('updatedAt', DateType::class)			
		->add('licence', TextType::class)
		->add('filename2')
		->add('imageFile2', FileType::class, ['label' => 'certif medical' ,  'required' => false ] )
		->add('updatedAt2', DateType::class)		
		->add('roles', ChoiceType::class, array(
			'choices'  => array('Choose your role' => 'Choose your role',
			'ROLE_USER' => 'ROLE_USER',
			'ROLE_ADMIN' => 'ROLE_ADMIN',
			'ROLE_MEMBRE' => 'ROLE_MEMBRE',),
			'multiple'     => true,
					))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}