<?php

namespace App\Form;

use App\Entity\Liste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ListeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
				'label' => 'Titre ',
            	'attr' => ['placeholder' => 'Titre', 'class' => 'required'],
				'required' => true,

			])
			->add('famille', TextType::class, [
				'attr' => ['placeholder' => 'Votre famille', 'class' => 'required'],
				'required' => true,

			])
            ->add('description', TextareaType::class, [
				'attr' => ['class' => 'tinymce', 'placeholder' => 'Ici votre description de la liste de naissance ...'],
			])
			->add('end_date', DateType::class, [
				'widget' => 'single_text',
				// prevents rendering it as type="date", to avoid HTML5 date pickers
				'html5' => true,

				// adds a class that can be selected in JavaScript
				'attr' => ['class' => 'js-datepicker'],
			])
            ->add('children', TextType::class, [
				'label' => 'Prénom de l\'enfant',
				'attr' => ['placeholder' => 'Facultatif'],
				'required' => false,

			])
			->add('genre', ChoiceType::class,[
				'label' => 'Fille ou Garcon',
				'attr' => ['form-select'],
				'choices' => [
					'Fille' => 'fille',
					'Garcon' => 'garcon',
				]
			])
            ->add('adresse', TextType::class, [
				'attr' => ['placeholder' => '2 rue des parents, 22300, Lannion'],

			])
			->add('image_file', FileType::class, [
				// unmapped means that this field is not associated to any entity property
				'label' => 'Image de votre liste',
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
			->add('Envoyer',
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
