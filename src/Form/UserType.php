<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Projet;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //On ajoute le champ "image" dans le formulaire
            //il n'est pas lié a la base de données
            ->add('image', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
            ])

            ->add('email', TextType::class, [
                'attr' => [
                    'placeholder' => "xxxxx@capgemini.com"
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot de passe doivent être identiques.',
                'options' => ['attr' => ['class' => 'Mot de passe']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer votre nom d'utilisateur"
                ]
            ])
            ->add('projet', EntityType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'class' => Projet::class,
            ])
            ->add('poste', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Développeur' => 'Développeur',
                    'Testeur' => 'Testeur',
                    'Business analyste' => 'Business analyste',
                    'Manager' => 'Manager',
                ],
            ])
            ->add('site', TextType::class, [
                'attr' => [
                    'placeholder' => "Veuillez entrer votre site"
                ]
            ]);

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
