<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Tag;
use App\Form\TagType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\DateTimePickerType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
			->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
			    'multiple' => true,
               
            ])
			
            ->add('filename')
			->add('taille')
			->add('imageFile', FileType::class, [ 'required' => false ])
			->add('updatedAt', DateType::class)
	    ;
    }
	//	array [ 'class' => Tag::class,  'choice_label' => 'name',])
	//  	->add('tags', EntityType::class, [ 
	//  ->add('roles', ChoiceType::class, array(
	//     'empty_data' => 'Aucun',
	//   'expanded' => true,
	//   	'placeholder' => '- - - -',
	//  	'label' => 'Ajouter des categories',
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
