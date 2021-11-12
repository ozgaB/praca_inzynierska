<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use App\Form\Type\TrainingPlanExerciseType;
use App\Entity\TrainingPlan\TrainingPlanDay;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\TrainingPlan\TrainingPlanExercise;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrainingPlanDayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'dayName',
                TextType::class,
                [
                    'label' => 'Day Name',
                ]
            )
            ->add(
                'trainingPlanExercise',
                CollectionType::class,
                [
                    'entry_type' => TrainingPlanExerciseType::class,
                    'entry_options' => [
                        'label' => true
                    ],
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
                )
            ->add(
                'saveAndAdd',
                SubmitType::class,
                [
                    'label' => 'Zapisz i dodaj następny dzień'
                ]
                )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Zapisz i wyjdź',
                ]
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrainingPlanDay::class,
            'methods' => 'POST',
        ]);
    }

}


?>