<?php

// src/Form/ObjetsConnectesType.php

namespace App\Form;

use App\Entity\ObjetsConnectes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjetsConnectesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'objet',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Nom']
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Ex: Thermostat, Caméra']
            ])
            ->add('marque', TextType::class, [
                'label' => 'Marque',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Marque']
            ])
            ->add('etat', TextType::class, [
                'label' => 'État',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Connecté / Déconnecté']
            ])
            ->add('consommationEnergetique', NumberType::class, [
                'label' => 'Consommation (kWh)',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Ex: 12.5']
            ])
            // Vous pouvez ajouter d'autres champs spécifiques aux objets connectés ici
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter l\'objet connecté',
                'attr'  => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ObjetsConnectes::class,
        ]);
    }
}
