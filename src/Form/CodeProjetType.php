<?php

namespace App\Form;

use App\Entity\CodeProjet;
use App\Entity\Projet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CodeProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer votre Code projet"
                ]
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer une description"
                ]
            ])
            ->add('statut')
            ->add('budget', IntegerType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer votre budget"
                ]
            ])
            ->add('dateD', DateType::class)
            ->add('DateF', DateType::class)

            ->add('projet', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'class' => Projet::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CodeProjet::class,
        ]);
    }
}
