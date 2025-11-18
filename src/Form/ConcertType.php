<?php

namespace App\Form;

use App\Entity\Concert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('band', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Avec quel groupe ? (optionnel)',
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
                    'placeholder' => 'Lieu du concert',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Concert::class,
        ]);
    }
}
