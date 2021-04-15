<?php

namespace App\Form;

use App\Entity\User;
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
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
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
            ->add('departement', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Informatique' => 'Informatique',
                    'Ressource humaine' => 'Ressource humaine',
                    'Financier' => 'Financier',
                    'Sécurité' => 'Sécurité',
                    'Communication' => 'Communication',
                ],
            ])
            ->add('poste', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Développeur' => 'Développeur',
                    'Team lead' => 'Team lead',
                    'Scrum master' => 'Scrum master',
                    'Manager' => 'Manager',
                    'Chef de projet' => 'Chef de projet',
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
