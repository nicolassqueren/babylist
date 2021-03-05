<?php

namespace App\Form;

use App\Entity\Objet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ObjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
			->add('image_file', FileType::class, [
				// unmapped means that this field is not associated to any entity property
				'mapped' => false,
				'required' => false, 'constraints' => [
					new File([
						'maxSize' => '1024k',
						'mimeTypesMessage' => 'Please upload a More lighter image',
					])
				],
			])
            ->add('link')
            ->add('state')
            ->add('brand')
			->add('datechoice', ChoiceType::class, [
				'choices'  => [
					'Avant la naissance' => "Avant la naissance",
					'Apres la naissance' => "Après la naissance",
				]
			])
			->add('Envoyer',
				SubmitType::class,
				[
					'attr' => [
						'class' => 'btn-primary',
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
