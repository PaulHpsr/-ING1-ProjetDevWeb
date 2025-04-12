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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'attr'  => ['placeholder' => 'Votre pseudo'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 1, 
                        'max' => 20,
                        'minMessage' => 'Le pseudo doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le pseudo ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9_]+$/', // Seulement lettres, chiffres
                        'message' => 'Le pseudo ne doit contenir que des lettres, des chiffres ou des underscores.',
                    ])
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr'  => ['placeholder' => 'Votre prénom'],
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr'  => ['placeholder' => 'Votre nom'],
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr'  => ['placeholder' => 'Votre email'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(['message' => 'Veuillez entrer un email valide.']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr'  => ['placeholder' => 'Votre mot de passe'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 8, 
                        'max' => 20,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'message' => 'Le mot de passe doit comporter au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.',
                    ])
                ]
            ])
            ->add('birthdate', DateType::class, [
                'label'  => 'Date de naissance',
                'widget' => 'single_text',
                'constraints' => [new Assert\NotBlank()],
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
                'attr'  => ['placeholder' => 'Exemple : Développeur, Testeur, etc.'],
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('profilePicture', FileType::class, [
                'required' => false,  
                'label'    => 'Photo de profil (optionnel)', 
                'mapped'   => false,   
                'attr'     => ['accept' => 'image/*'],
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
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
}
