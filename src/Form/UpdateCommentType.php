<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\Form\FormEvent;
// use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => [
                    'placeholder' => 'Modifier votre commentaire',
                    'rows' => '8'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => [
                    'class' => 'btn-block btn-info mx-auto'
                ]
            ])
            ->add('reset', ResetType::class, [//TODO reste ce bouton
                'label' => 'Annuler',
                'attr' => [
                    'class' => 'btn-block btn-secondary mx-auto'
                ]
            ])
        ;

        // $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();
        // })
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
