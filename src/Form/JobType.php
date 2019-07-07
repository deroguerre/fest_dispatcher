<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Team;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Début',
                'required' => true,
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Fin',
                'required' => true,
            ])
            ->add('backgroundColor',ColorType::class, [
                'label' => 'Couleur',
                'required' => false
            ])
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'label' => 'Equipe',
                'required' => true
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Bénévole',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
