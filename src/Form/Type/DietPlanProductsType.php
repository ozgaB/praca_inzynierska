<?php
namespace App\Form\Type;

use App\Entity\Security\User;
use Symfony\Component\Form\AbstractType;
use App\Entity\DietPlan\DietPlanProducts;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class DietPlanProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'mealGroup',
                ChoiceType::class,
                [
                    'choices'  => [
                        'Śniadanie' => 'sniadanie',
                        'Obiad' => 'obiad',
                        'Kolacja' => 'kolacja',
                    ],
                ]
            )
            ->add(
                'productName',
                TextType::class,
                [
                    'label' => 'Muscle Group',
                ]
            )
            ->add(
                'protein',
                IntegerType::class,
                [
                    'label' => 'Sets',
                ]
            )
            ->add(
                'carbo',
                IntegerType::class,
                [
                    'label' => 'Repetitions',
                ]
            )
            ->add(
                'fat',
                IntegerType::class,
                [
                    'label' => 'Exercise Time',
                ]
            )
            ->add(
                'kcl',
                IntegerType::class,
                [
                    'label' => 'Exercise Break',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DietPlanProducts::class,
            'methods' => 'POST',
            'attr' => ['class' => 'custom_inputs_add_form'],
        ]);
    }

}


?>