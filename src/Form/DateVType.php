<?php

namespace App\Form;

use App\Entity\DateV;
use App\Entity\Taches;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DateVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tache', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'class' => Taches::class,
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('valeur');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DateV::class,
        ]);
    }
}
