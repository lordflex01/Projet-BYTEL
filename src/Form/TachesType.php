<?php

namespace App\Form;

use App\Entity\Taches;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\CodeProjet;

class TachesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer votre nom de tache"
                ]
            ])
            ->add('description', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'DEV/TU' => 'DEV/TU',
                    'Cunit' => 'Cunit',
                    'TC' => 'TC',
                    'Réunion' => 'Réunion',
                    'Cérémonie' => 'Cérémonie',
                    'Conception' => 'Conception',
                    'Tec lead' => 'Tec lead',
                ],
            ])
            ->add('codeprojet', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'class' => CodeProjet::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Taches::class,
        ]);
    }
}
