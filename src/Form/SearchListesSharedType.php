<?php

namespace App\Form;

use App\Entity\Liste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchListesSharedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('famille', TextType::class, [
            	'attr' => [
            		'placeholder' => 'Nom de la famille :'
				],
				'constraints' => [
					new NotBlank([
						'message' => 'Merci de mettre le nom de famille recherchez',
					]),
					new Length([
						// max length allowed by Symfony for security reasons
						'max' => 25,
						'maxMessage' => 'Votre recherche ne doit pas dépasser {{ limit }} caractères',
					]),
				],
			])
			->add('Rechercher',
				SubmitType::class,
				[
					'attr' => [
						'class' => 'liste_btn btn_action btn',
					]
				]
			)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Liste::class,
        ]);
    }
}
