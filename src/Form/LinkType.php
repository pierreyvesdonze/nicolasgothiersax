<?php

namespace App\Form;

use App\Entity\Link;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Site'        => 'site',
                    'Youtube'     => 'youtube',
                    'Instagram'   => 'instagram',
                    'Facebook'    => 'facebook',
                    'Tiktok'      => 'tiktok',
                    'Linkedin'    => 'linkedin',
                    'Linkaband'   => 'linkaband',
                    'Soundcloud'  => 'soundcloud',
                    'Mariage.net' => 'mariage',
                ],
                'required'    => true,
                'placeholder' => 'Sélectionnez un type',
            ])
            ->add('path', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Lien URL',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Link::class,
        ]);
    }
}
