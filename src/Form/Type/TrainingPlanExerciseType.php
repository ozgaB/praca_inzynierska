<?php
namespace App\Form\Type;

use App\Entity\Security\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\TrainingPlan\TrainingPlanExercise;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class TrainingPlanExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'exerciseName',
                TextType::class,
                [
                    'label' => 'Exercise Name',
                ]
            )
            ->add(
                'muscleGroup',
                TextType::class,
                [
                    'label' => 'Muscle Group',
                ]
            )
            ->add(
                'sets',
                IntegerType::class,
                [
                    'label' => 'Sets',
                ]
            )
            ->add(
                'repetition',
                IntegerType::class,
                [
                    'label' => 'Repetitions',
                ]
            )
            ->add(
                'time',
                TextType::class,
                [
                    'label' => 'Exercise Time',
                ]
            )
            ->add(
                'break',
                TextType::class,
                [
                    'label' => 'Exercise Break',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrainingPlanExercise::class,
            'methods' => 'POST',
            'attr' => ['class' => 'custom_inputs_add_form'],
        ]);
    }

}


?>