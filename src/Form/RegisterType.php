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

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)  // Champ pour le pseudo
            ->add('firstName', TextType::class)  // Champ pour le prénom
            ->add('lastName', TextType::class)  // Champ pour le nom
            ->add('email', EmailType::class)  // Champ pour l'email
            ->add('password', PasswordType::class)  // Champ pour le mot de passe
            ->add('birthdate', DateType::class)  // Champ pour la date de naissance
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ],
            ])
            ->add('memberType', TextType::class)  // Champ pour le type de membre
            ->add('profilePicture', FileType::class, [
                'required' => false,  // Optionnel, l'utilisateur peut télécharger une photo de profil
                'label' => 'Photo de profil (optionnel)', 
                'mapped' => false,  // Ne pas lier ce champ directement à l'entité User
                'attr' => ['accept' => 'image/*'], // Limite aux images
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,  // Lier ce formulaire à l'entité User
        ]);
    }
}




