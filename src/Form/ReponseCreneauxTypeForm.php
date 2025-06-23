<?php



namespace App\Form;

use App\Entity\ReponsesCreneaux;
use App\Entity\Creneaux;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseCreneauxTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $creneaux = $options['creneaux'] ?? [];

        $builder
            ->add('creneaux', EntityType::class, [
                'class' => Creneaux::class,
                'choices' => $creneaux,
                'choice_label' => function (Creneaux $creneau) {
                    return $creneau->getStartTime()->format('H:i') . ' - ' . $creneau->getEndTime()->format('H:i');
                },
                'disabled' => true,
                'label' => false,
            ])
            ->add('confirmer', CheckboxType::class, [
                'label' => 'Confirmer ce créneau',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponsesCreneaux::class,
            'creneaux' => [],
        ]);
    }
}
