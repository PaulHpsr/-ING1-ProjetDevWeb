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
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le contenu détaillé de l\'info',
                    'rows' => 6,
                ],
            ])
            ->add('publishDate', DateTimeType::class, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('publisher', EntityType::class, [
                'label' => 'Publié par',
                'class' => User::class,
                'choice_label' => 'email', // Changez par une autre propriété comme `username` si nécessaire
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('imagePath', FileType::class, [
                'label' => 'Image (PNG, JPG, JPEG, GIF)',
                'mapped' => false, // Ce champ n'est pas directement mappé à la propriété de l'entité
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
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
