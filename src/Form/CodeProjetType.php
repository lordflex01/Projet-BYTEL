<?php

namespace App\Form;

use App\Entity\CodeProjet;
use App\Entity\Projet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
                    'placeholder' => "Veuillez entrer une description",
                    
                ],
                
            ])
            ->add('statut', CheckboxType::class, [
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])
            ->add('budget', IntegerType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer votre budget"
                ],
                'required' => false,
            ])
            ->add('budgetNRJ', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer le budget d'NRJ"
                ],
                'required' => false,
            ])
            ->add('budgetDECO', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer le budget de DECO"
                ],
                'required' => false,
            ])
            ->add('budgetCLOE', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer le budget de CLOE"
                ],
                'required' => false,
            ])
            ->add('chageJH', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer la charge en jour-homme"
                ],
                'required' => false,
            ])
            ->add('chageNRJ', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer la charge en jour-homme pour le projet NRJ"
                ],
                'required' => false,
            ])
            ->add('chageDECO', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer la charge en jour-homme pour le projet DECO"
                ],
                'required' => false,
            ])
            ->add('chargeCLOE', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer la charge en jour-homme pour le projet CLOE"
                ],
                'required' => false,
            ])
            ->add('dateD', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('DateF', DateType::class, [
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CodeProjet::class,
        ]);
    }
}
