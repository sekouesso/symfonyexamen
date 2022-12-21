<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\User;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('couleur')
            ->add('numeroChassi')
            ->add('nombreSiege')
            ->add('annee')
            ->add('kilometrage')
            ->add('user', EntityType::class,[
                'class' => User::class,
                'choice_label'=> 'email',
                'attr'=>[
                    'class'=>'form-control mt-2'
                    ]
                ])
            ->add('marque', EntityType::class,[
                'class' => Marque::class,
                'choice_label'=> 'libelle',
                'attr'=>[
                    'class'=>'form-control mt-2'
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
