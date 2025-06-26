<?php

namespace App\Form;

use App\Entity\Creneaux;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReunionConfirmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('creneau', EntityType::class, [
                'class' => Creneaux::class,
                'choices' => $options['creneaux'],
                'choice_label' => function (Creneaux $creneau) {
                    return $creneau->getStartTime()->format('d/m/Y H:i') . ' - ' . $creneau->getEndTime()->format('H:i');
                },
                'label' => 'Sélectionner le créneau à confirmer',
                'mapped' => false,
                'required' => true,
                'expanded' => true, // radio buttons
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'creneaux' => [],
        ]);
    }
}

