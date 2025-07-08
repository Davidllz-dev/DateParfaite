<?php

namespace App\Form;

use App\Entity\Reponses;
use App\Entity\Creneaux;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('commentaires', TextType::class, [
                'label' => 'Commentaires (facultatif)',
                'required' => false,
            ])
            ->add('valider', CheckboxType::class, [
                'label' => 'Cocher si vous confirmez votre participation à la réunion',
                'required' => false,
            ])
            
            
            ->add('reponsesCreneauxes', EntityType::class, [
                'class' => Creneaux::class,
                'choices' => $options['creneaux'],
                'choice_label' => function (Creneaux $creneau) {
                    return $creneau->getStartTime()->format('d/m/Y') . ' de ' .
                        $creneau->getStartTime()->format('H:i') . ' à ' .
                        $creneau->getEndTime()->format('H:i');
                },

                'multiple' => true,
                'expanded' => true,
                'mapped' => false, 
                'label' => 'Créneaux disponibles',
            ]);
            // ->add('submit', SubmitType::class, [
            //     'label' => 'Envoyer la réponse'
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponses::class,
            'creneaux' => [],
        ]);
    }
}
