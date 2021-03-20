<?php

namespace App\Form;

use App\Entity\Objet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ObjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title' , TextType::class, [
				'label' => 'Titre',
				'attr' => ['placeholder' => 'Objet ..', 'class' => 'required'],
				'required' => true,

			])
            ->add('description', TextareaType::class, [
				'attr' => ['class' => 'tinymce', 'placeholder' => 'Ici votre description de l\'objet ...'],
			])
			->add('image_file', FileType::class, [
				// unmapped means that this field is not associated to any entity property
				'label' => 'Image de l\'objet',
				'attr' => [
					'class' => 'form-control',
				],
				'mapped' => false,
				'required' => false, 'constraints' => [
					new File([
						'maxSize' => '1024k',
						'mimeTypesMessage' => 'Please upload a More lighter image',
					])
				],
			])
            ->add('link', TextType::class, [
				'label' => 'Lien',
				'attr' => ['placeholder' => 'https://www.example.com'],
				'required' => false

			])
            ->add('state', TextType::class, [
				'label' => 'Etat',
				'attr' => ['placeholder' => 'Occasion, Neuf ..']

			])
            ->add('brand' , TextType::class, [
				'label' => 'Marque',
				'attr' => ['placeholder' => 'Marque', 'class' => 'required'],
				'required' => false,
			])
			->add('datechoice', ChoiceType::class, [
				'label' => 'Livraison ',
				'attr' => ['class' => 'form-select'],
				'choices'  => [
					'Avant la naissance' => "Avant la naissance",
					'Apres la naissance' => "Après la naissance",
				]
			])
			->add('Envoyer',
				SubmitType::class,
				[
					'label' => 'Valider',
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
            'data_class' => Objet::class,
        ]);
    }
}
