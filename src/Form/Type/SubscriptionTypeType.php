<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use App\Entity\Subscription\SubscriptionType;
use App\Entity\TrainingPlan\TrainingPlanList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SubscriptionTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Name',
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'Description',
                ]
            )
            ->add(
                'lifeTimeInSeconds',
                IntegerType::class,
                [
                    'label' => 'Life time in seconds',
                ]
            )
            ->add(
                'pricePln',
                IntegerType::class,
                [
                    'label' => 'Price in PLN',
                ]
            )
            ->add(
                'priceEur',
                IntegerType::class,
                [
                    'label' => 'Price in EUR',
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
            'data_class' => SubscriptionType::class,
            'methods' => 'POST',
        ]);
    }

}


?>