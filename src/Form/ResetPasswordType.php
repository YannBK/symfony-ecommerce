<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('new_password', RepeatedType::class, [ //RepeatedType permet de mettre deux inputs de même type dont le contenu doit être identique
            'type'=> PasswordType::class,
            'invalid_message'=>'Le mot de passe et la confirmation doivent être identiques.',
            'label'=>'Votre nouveau mot de passe',
            'required'=>true,
            'first_options'=>[
                'label'=>'Saisissez nouveau mot de passe',
                'attr'=> [
                    'placeholder'=>'Saisissez votre nouveau mot de passe'
                ]
            ],
            'second_options'=>[
                'label'=>'Confirmez votre nouveau mot de passe',
                'attr'=> [
                    'placeholder'=>'Confirmer votre nouveau votre mot de passe'
                ]
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label'=>"Mettre à jour le mot de passe",
            'attr' => [
                'class' => 'btn-block btn-info'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
