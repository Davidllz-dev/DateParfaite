<?php


namespace App\Form;

use App\Entity\Reunions;
use App\Form\CreneauType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use App\Enum\ReunionStatus;

class ReunionTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('inviteEmails', CollectionType::class, [
                'entry_type' => EmailType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
                'label' => 'Emails des invités',
                'required' => true,
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre de la réunion',
                'required' => true,
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu',
                'required' => true,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut de la réunion',
                'choices' => [
                    'En attente' => ReunionStatus::EN_ATTENTE,
                    'Confirmée' => ReunionStatus::CONFIRMEE,
                    'Annulée' => ReunionStatus::ANNULEE,
                ],
                'required' => true,
            ])
            ->add('creneaux', CollectionType::class, [
                'entry_type' => CreneauTypeForm::class,
                'label' => 'Créneaux disponibles',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reunions::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'reunion_form',
        ]);
    }
}
