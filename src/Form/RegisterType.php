<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'=>'Votre prénom',
                'constraints'=> new Length(null, 2,20),
                'attr'=> [
                    'placeholder'=>'Saisissez votre prénom'
                ],
            ])
            ->add('lastname', TextType::class, [
                'label'=>'Votre nom',
                'constraints'=> new Length(null, 2,20),
                'attr'=> [
                    'placeholder'=>'Saisissez votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=>'Votre email',
                'attr'=> [
                    'placeholder'=>'Saisissez votre email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'invalid_message'=>'Le mot de passe et la confirmation doivent être identiques.',
                'label'=>'Votre mot de passe',
                'required'=>true,
                'constraints'=> new Regex('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,}$/m'),
                'first_options'=>[
                    'label'=>'Mot de passe',
                    'attr'=> [
                        'placeholder'=>'8 caractères dont 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial'
                    ],
                    'invalid_message'=>'Le mot de passe doit contenir au moins 8 caractères, 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial.'
                ],
                'second_options'=>[
                    'label'=>'Confirmez votre mot de passe',
                    'attr'=> [
                        'placeholder'=>'Confirmez votre mot de passe'
                    ],
                    'invalid_message' => 'Le mot de passe ne correspond pas.'
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label'=>"S'inscrire"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
