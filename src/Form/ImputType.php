<?php

namespace App\Form;

use App\Entity\Imput;
use App\Entity\User;
use App\Entity\Taches;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImputType extends AbstractType
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
            ->add('user', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'class' => User::class,
            ])
            ->add('DateVs', CollectionType::class, [
                'entry_type' => DateVType::class,
                'label' => 'Date et valeur d\'imputation',
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Imput::class,
        ]);
    }
}
