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

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'=>'Votre prénom',
                'constraints'=> new Length(null, 2,4),
                'attr'=> [
                    'placeholder'=>'Saisissez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label'=>'Votre nom',
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
            ->add('password', RepeatedType::class, [ //RepeatedType permet de mettre deux inputs de même type dont le contenu doit être identique
                'type'=> PasswordType::class,
                'invalid_message'=>'Le mot de passe et la confirmation doivent être identiques.',
                'label'=>'Votre mot de passe',
                'required'=>true,
                'first_options'=>[
                    'label'=>'Mot de passe',
                    'attr'=> [
                        'placeholder'=>'Saisissez votre mot de passe'
                    ]
                ],
                'second_options'=>[
                    'label'=>'Confirmez votre mot de passe',
                    'attr'=> [
                        'placeholder'=>'Saisissez votre mot de passe'
                    ]
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label'=>"S'inscrire"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
