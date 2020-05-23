<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Option;
use App\Entity\RoomSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('dateFrom', DateTimeType::class)
            ->add('dateTo', DateTimeType::class)
            ->add('minCapacity', IntegerType::class, [
                'required'  => false
            ])
            ->add('options', EntityType::class, [
                'required'  => false,
                'class'     => Option::class,
                'choice_label' => 'name',
                'multiple'  => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => RoomSearch::class,
            'method'        => 'get',
            'csrf_protection' =>  false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
