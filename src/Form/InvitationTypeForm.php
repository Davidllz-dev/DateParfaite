<?php
namespace App\Form;

use App\Entity\Invitations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvitationTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reponse', ChoiceType::class, [
                'choices' => [
                    'Je participe' => 'oui',
                    'Je ne participe pas' => 'non',
                ],
                'expanded' => true, // Affiche en boutons radio
                'label' => 'Votre réponse',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invitations::class,
        ]);
    }
}
