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

class ObjetsConnectesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'objet',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Nom'],
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
            ])
            ->add('marque', TextType::class, [
                'label' => 'Marque',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Marque'],
            ])
            ->add('etat', TextType::class, [
                'label' => 'État',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Connecté / Déconnecté'],
            ])
            ->add('consommationEnergetique', NumberType::class, [
                'label' => 'Consommation (kWh)',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Ex: 12.5'],
            ])

            // Champs pour Thermostat
            ->add('temperatureActuelle', NumberType::class, [
                'label'    => 'Température actuelle',
                'required' => false,
            ])
            ->add('temperatureCible', NumberType::class, [
                'label'    => 'Température cible',
                'required' => false,
            ])

            // Champs pour Porte
            ->add('etatPorte', TextType::class, [
                'label'    => 'État de la porte',
                'required' => false,
            ])
            ->add('etatBatterie', NumberType::class, [
                'label'    => 'État de la batterie (%)',
                'required' => false,
            ])

            // Champs pour Caméra
            ->add('resolutionCamera', TextType::class, [
                'label'    => 'Résolution',
                'required' => false,
            ])
            ->add('angleVision', NumberType::class, [
                'label'    => 'Angle de vision (°)',
                'required' => false,
            ])

            // Champs pour Photocopieuse
            ->add('niveauStockMAX', NumberType::class, [
                'label'    => 'Niveau de stock maximum',
                'required' => false,
            ])
            ->add('niveauStock', NumberType::class, [
                'label'    => 'Niveau de stock actuel',
                'required' => false,
            ])
            ->add('nombreCopiesParJour', NumberType::class, [
                'label'    => 'Nombre de copies par jour',
                'required' => false,
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
