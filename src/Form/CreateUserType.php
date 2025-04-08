<?php

namespace App\Form;

use App\Entity\PurchasedType;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateUserType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    ){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('filter_email'),
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => $this->translator->trans('filter_firstName'),
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'label' => $this->translator->trans('filter_lastName'),
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('source', EntityType::class, [
                'label' => $this->translator->trans('filter_source'),
                'class' => PurchasedType::class,
                'choice_label' => function($source){
                    return $this->translator->trans($source->getName());
                },
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'label' => $this->translator->trans('filter_roles'),
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Guest' => 'ROLE_GUEST'
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'multiple' => true,
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'label' => $this->translator->trans('filter_password'),
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'mapped' => false,
            ])
            ->add('profilePicture', FileType::class, [
                'label' => $this->translator->trans('filter_profile_picture'),
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
