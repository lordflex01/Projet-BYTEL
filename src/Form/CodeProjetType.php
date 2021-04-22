<?php

namespace App\Form;

use App\Entity\CodeProjet;
use App\Entity\Projet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CodeProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('statut')
            ->add('budget')
            ->add('dateD')
            ->add('DateF')
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
