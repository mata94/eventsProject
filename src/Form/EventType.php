<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('location',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('status', EntityType::class, [
                'class' => EventStatus::class,
                'choice_label' => function($status){
                    return $status->getName();
                },
                'attr' => [
                    'class' => 'form-control',
                ],

            ])
            ->add('picture', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPEG,JPG or PNG image',
                    ])
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
