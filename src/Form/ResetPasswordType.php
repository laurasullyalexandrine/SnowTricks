<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => true,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'first_options' => [
                    'attr' => [
                        'placeholder' => 'Nouveau mot de passe',
                        'class' => 'form-control'
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'Confirmes ton nouveau mot de passe',
                        'class' => 'form-control'
                    ]
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir ton nouveau mot de passe'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Ton mot de passe doit être au moins {{ limit }} caractère',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
