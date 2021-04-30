<?php

namespace App\Form;

use App\Entity\CodeProjet;
use App\Entity\User;
use App\Entity\Imputation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ImputationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentaire', TextareaType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer votre commentaire"
                ]
            ])
            ->add('dateD', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('dateF', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('codeprojet', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'class' => CodeProjet::class,
            ])
            ->add('user', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'disabled' => true,
                'class' => User::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Imputation::class,
        ]);
    }
}
