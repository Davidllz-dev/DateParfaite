<?php


namespace App\Form;

use App\Entity\Reponses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $creneaux = $options['creneaux'] ?? [];

        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('commentaires', TextType::class, [
                'required' => false,
            ])
            ->add('valider', CheckboxType::class, [
                'label' => 'Confirmer votre participation',
                'required' => false,
            ])
            ->add('reponsesCreneauxes', CollectionType::class, [
                'entry_type' => ReponseCreneauxTypeForm::class,
                'entry_options' => ['creneaux' => $creneaux],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer la réponse'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponses::class,
            'creneaux' => [],
        ]);
    }
}
