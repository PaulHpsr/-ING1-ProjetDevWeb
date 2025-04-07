<?php

// src/Form/RegisterType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Champs de base pour l'inscription
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'attr'  => ['placeholder' => 'Votre pseudo']
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr'  => ['placeholder' => 'Votre prénom']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr'  => ['placeholder' => 'Votre nom']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr'  => ['placeholder' => 'Votre email']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr'  => ['placeholder' => 'Votre mot de passe']
            ])
            ->add('birthdate', DateType::class, [
                'label'  => 'Date de naissance',
                'widget' => 'single_text',
            ])
            ->add('sex', ChoiceType::class, [
                'label'   => 'Sexe',
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ],
            ])
            ->add('memberType', TextType::class, [
                'label' => 'Type de membre',
                'attr'  => ['placeholder' => 'Exemple : Développeur, Testeur, etc.']
            ])
            ->add('profilePicture', FileType::class, [
                'required' => false,  // Optionnel
                'label'    => 'Photo de profil (optionnel)', 
                'mapped'   => false,   // Ne lie pas ce champ directement à l'entité User
                'attr'     => ['accept' => 'image/*'],
                'constraints' => [
                    new File([
                        'maxSize'          => '2M',
                        'mimeTypes'        => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (PNG, JPG, JPEG, GIF).',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Lier le formulaire à l'entité User
            'data_class' => User::class,
        ]);
    }
}
