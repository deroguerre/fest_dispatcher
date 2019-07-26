<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\VolunteerAvailability;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolunteerAvailabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                "class" => User::class
            ])
            ->add('festival')
            ->add('startDate')
            ->add('endDate')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VolunteerAvailability::class,
        ]);
    }
}
