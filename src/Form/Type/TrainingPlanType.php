<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use App\Entity\TrainingPlan\TrainingPlanList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrainingPlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'planName',
                TextType::class,
                [
                    'label' => '',
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => '',
                ]
            )
            ->add(
                'saveAndAdd',
                SubmitType::class,
                [
                    'label' => 'Zapisz i dodaj nowy dzień treningowy'
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
            'data_class' => TrainingPlanList::class,
            'methods' => 'POST',
        ]);
    }

}


?>