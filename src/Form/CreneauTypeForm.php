<?php

namespace App\Form;

use App\Entity\Creneaux;
use App\Entity\Reunions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreneauTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
         $builder
            ->add('start_time', DateTimeType::class, [
                'label' => 'Début',
                'widget' => 'single_text',
                'html5' => false, 
            ])
            ->add('end_time', DateTimeType::class, [
                'label' => 'Fin',
                'widget' => 'single_text',
                'html5' => false, 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Creneaux::class,
        ]);
    }
}
