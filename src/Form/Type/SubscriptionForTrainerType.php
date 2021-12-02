<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use App\Entity\Subscription\Subscription;
use App\Entity\Subscription\SubscriptionType;
use App\Entity\TrainingPlan\TrainingPlanList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SubscriptionForTrainerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add(
            'createdAt',
            DateTimeType::class,
            [
                'label' => 'Created At',
            ]
        )
            ->add(
                'expireAt',
                DateTimeType::class,
                [
                    'label' => 'Expire At',
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
            'data_class' => Subscription::class,
            'methods' => 'POST',
        ]);
    }
}
