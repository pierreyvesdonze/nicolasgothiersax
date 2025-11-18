<?php

namespace App\Form;

use App\Entity\Degree;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DegreeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Titre du diplôme',
                ],
            ])
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'format' => 'dd-MM-yyyy', // obligatoire pour Symfony
                'years' => range(date('Y'), 1990),
                'required' => true,
                'attr' => ['class' => 'uk-select'],
            ])
            ->add('place', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Lieux où tu as eu le diplôme',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Degree::class,
        ]);
    }
}
