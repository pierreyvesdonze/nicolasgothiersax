<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('path', FileType::class, [
                'label' => 'Charger des images',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '5M',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/png',
                                    'image/gif',
                                    'image/webp',
                                ],
                                'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF, WEBP).',
                            ])
                        ]
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
