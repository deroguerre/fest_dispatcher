<?php

namespace App\Form;

use App\Entity\Festival;
use App\Entity\Team;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name *',
                'required' => true
            ])
            ->add('description')
            ->add('festival', EntityType::class, [
                'class' => Festival::class,
                'label' => 'Festival *'
            ])
            ->add('managers', EntityType::class, [
                'class' => User::class,
                'label' => 'Managers',
                'multiple' => true,
                'required' => false
            ])
            ->add('volunteers', EntityType::class, [
                'class' => User::class,
                'label' => 'Volunteers',
                'multiple' => true,
                'required' => false
            ])
            ->add('neededVolunteers')
            ->add('backgroundColor', ColorType::class, [
                'label' => 'Background color'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
