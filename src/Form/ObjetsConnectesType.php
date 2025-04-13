<?php

namespace App\Form;

use App\Entity\ObjetsConnectes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints as Assert;

class ObjetsConnectesType extends AbstractType
{


public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        // Champs de base pour l'objet connecté
        ->add('nom', TextType::class, [
            'label' => 'Nom de l\'objet',
            'attr'  => ['class' => 'form-control', 'placeholder' => 'Nom'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le nom de l\'objet ne peut pas être vide.']),
                new Assert\Length([
                    'min' => 3,
                    'max' => 50,
                    'minMessage' => 'Le nom de l\'objet doit contenir au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le nom de l\'objet doit contenir au maximum {{ limit }} caractères.',
                ]),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z0-9\s\-]+$/', // Limite aux lettres, chiffres, espaces et tirets
                    'message' => 'Le nom de l\'objet ne peut contenir que des lettres, des chiffres, des espaces et des tirets.',
                ])
            ]
        ])
        ->add('type', ChoiceType::class, [
            'label' => 'Type',
            'choices' => [
                'Thermostat'   => 'Thermostat',
                'Porte'        => 'Porte',
                'Caméra'       => 'Caméra',
                'Photocopieuse'=> 'Photocopieuse',
            ],
            'placeholder' => 'Choisissez un type',
            'attr'  => ['class' => 'form-control'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'Veuillez sélectionner un type.']),
            ]
        ])
        ->add('marque', TextType::class, [
            'label' => 'Marque',
            'attr'  => ['class' => 'form-control', 'placeholder' => 'Marque'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le champ marque ne peut pas être vide.']),
                new Assert\Length([
                    'min' => 2,
                    'max' => 50,
                    'minMessage' => 'Le nom de la marque doit contenir au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le nom de la marque doit contenir au maximum {{ limit }} caractères.',
                ]),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z0-9\s\-]+$/', // Limite aux lettres, chiffres, espaces et tirets
                    'message' => 'La marque ne peut contenir que des lettres, des chiffres, des espaces et des tirets.',
                ])
            ]
        ])
        ->add('etat', TextType::class, [
            'label' => 'État',
            'attr'  => ['class' => 'form-control', 'placeholder' => 'Connecté / Déconnecté'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le champ état ne peut pas être vide.']),
                new Assert\Choice([
                    'choices' => ['Connecté', 'Déconnecté'],
                    'message' => 'L\'état doit être soit "Connecté" soit "Déconnecté".'
                ])
            ]
        ])
        ->add('consommationEnergetique', NumberType::class, [
            'label' => 'Consommation (kWh)',
            'attr'  => ['class' => 'form-control', 'placeholder' => 'Ex: 12.5'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'La consommation énergétique ne peut pas être vide.']),
                new Assert\Range([
                    'min' => 0,
                    'max' => 10000,
                    'notInRangeMessage' => 'La consommation doit être entre {{ min }} et {{ max }} kWh.'
                ])
            ]
        ])

        // Champs pour Thermostat
        ->add('temperatureActuelle', NumberType::class, [
            'label'    => 'Température actuelle',
            'required' => false,
            'constraints' => [
                new Assert\Range([
                    'min' => -50,
                    'max' => 100,
                    'notInRangeMessage' => 'La température actuelle doit être comprise entre {{ min }} et {{ max }}°C.'
                ])
            ]
        ])
        ->add('temperatureCible', NumberType::class, [
            'label'    => 'Température cible',
            'required' => false,
            'constraints' => [
                new Assert\Range([
                    'min' => -50,
                    'max' => 100,
                    'notInRangeMessage' => 'La température cible doit être comprise entre {{ min }} et {{ max }}°C.'
                ])
            ]
        ])

        // Champs pour Porte
        ->add('etatPorte', TextType::class, [
            'label'    => 'État de la porte',
            'required' => false,
            'constraints' => [
                new Assert\Choice([
                    'choices' => ['Ouverte', 'Fermée'],
                    'message' => 'L\'état de la porte doit être soit "Ouverte" soit "Fermée".',
                ])
            ]
        ])
        ->add('etatBatterie', NumberType::class, [
            'label'    => 'État de la batterie (%)',
            'required' => false,
            'constraints' => [
                new Assert\Range([
                    'min' => 0,
                    'max' => 100,
                    'notInRangeMessage' => 'L\'état de la batterie doit être entre {{ min }}% et {{ max }}%.'
                ])
            ]
        ])

        // Champs pour Caméra
        ->add('resolutionCamera', TextType::class, [
            'label'    => 'Résolution',
            'required' => false,
            'constraints' => [
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z0-9xX\s]+$/', // Limite aux lettres, chiffres et 'x' pour les résolutions comme "1920x1080"
                    'message' => 'La résolution doit être un format valide comme "1920x1080".',
                ])
            ]
        ])
        ->add('angleVision', NumberType::class, [
            'label'    => 'Angle de vision (°)',
            'required' => false,
            'constraints' => [
                new Assert\Range([
                    'min' => 0,
                    'max' => 180,
                    'notInRangeMessage' => 'L\'angle de vision doit être compris entre {{ min }}° et {{ max }}°.'
                ])
            ]
        ])

        // Champs pour Photocopieuse
        ->add('niveauStockMAX', NumberType::class, [
            'label'    => 'Niveau de stock maximum',
            'required' => false,
            'constraints' => [
                new Assert\Range([
                    'min' => 0,
                    'max' => 1000,
                    'notInRangeMessage' => 'Le niveau de stock maximum doit être entre {{ min }} et {{ max }}.'
                ])
            ]
        ])
        ->add('niveauStock', NumberType::class, [
            'label'    => 'Niveau de stock actuel',
            'required' => false,
            'constraints' => [
                new Assert\Range([
                    'min' => 0,
                    'max' => 1000,
                    'notInRangeMessage' => 'Le niveau de stock actuel doit être entre {{ min }} et {{ max }}.'
                ])
            ]
        ])
        ->add('nombreCopiesParJour', NumberType::class, [
            'label'    => 'Nombre de copies par jour',
            'required' => false,
            'constraints' => [
                new Assert\Range([
                    'min' => 0,
                    'max' => 10000,
                    'notInRangeMessage' => 'Le nombre de copies par jour doit être entre {{ min }} et {{ max }}.'
                ])
            ]
        ])

        ->add('save', SubmitType::class, [
            'label' => 'Ajouter l\'objet connecté',
            'attr'  => ['class' => 'btn btn-primary'],
        ])
    ;
}



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ObjetsConnectes::class,
        ]);
    }
}
