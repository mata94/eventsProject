<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterFormType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    ){}


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('filter_email'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your email',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => $this->translator->trans('filter_password'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 500,
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
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
