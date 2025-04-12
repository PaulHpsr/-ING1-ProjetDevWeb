<?php

namespace App\Form;

use App\Entity\Infos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class InfosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le titre de l\'info',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le titre ne peut pas être vide.']),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 100,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\s\-\_\&\#\;\!]+$/', // Autorise seulement les lettres, chiffres, espaces, tirets, etc.
                        'message' => 'Le titre contient des caractères invalides.',
                    ])
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le contenu détaillé de l\'info',
                    'rows' => 6,
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le contenu ne peut pas être vide.']),
                    new Assert\Length([
                        'min' => 10,
                        'max' => 2000,
                        'minMessage' => 'Le contenu doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le contenu ne peut pas dépasser {{ limit }} caractères.',
                    ])
                ],
            ])
            ->add('publishDate', DateTimeType::class, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de publication ne peut pas être vide.']),
                    new Assert\DateTime(['message' => 'Veuillez entrer une date valide.'])
                ],
            ])
            ->add('publisher', EntityType::class, [
                'label' => 'Publié par',
                'class' => User::class,
                'choice_label' => 'email', // Changez par une autre propriété comme `username` si nécessaire
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le champ "Publié par" est obligatoire.']),
                ]
            ])
            ->add('imagePath', FileType::class, [
                'label'    => 'Image (PNG, JPG, JPEG, GIF)',
                'required' => false,
                'mapped'   => false,
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Infos::class, // L'entité associée au formulaire
        ]);
    }
}
