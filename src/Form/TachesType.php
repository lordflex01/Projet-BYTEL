<?php

namespace App\Form;

use App\Entity\Taches;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer une description"
                ],
            ])
            ->add('domaine', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    'NRJ' => 'NRJ',
                    'DECO' => 'DECO',
                    'CLOE' => 'CLOE',
                ],
            ])
            ->add('codeprojet', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function ($codeprojet) {
                    return $codeprojet->getLibelle() . ': ' . $codeprojet->getDescription();
                },
                'attr' => [
                    'class' => "form-control select2"
                ],
                'class' => CodeProjet::class,
            ])
            ->add('statut', CheckboxType::class, [
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Taches::class,
        ]);
    }
}
