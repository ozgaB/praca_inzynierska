<?php
namespace App\Form\Type;

use App\Entity\Security\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\AddressAndContact\AddressAndContact;
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

class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => 'Imię',
                    'mapped' => false,
                    'attr' => [
                        'class' => 'm-2'
                    ],
                    'required' => false,
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'Nazwisko',
                    'mapped' => false,
                    'attr' => [
                        'class' => 'm-2'
                    ],
                    'required' => false,
                ]
            )
            ->add(
                'email',
                TextType::class,
                [
                    'label' => 'Email',
                    'mapped' => false,
                    'attr' => [
                        'class' => 'm-2'
                    ],
                    'required' => false,
                ]
            )
            ->add(
                'userRole',
                ChoiceType::class,
                [
                    'label' => 'Rola użytkownika',
                    'choices'  => [
                        'Role User' => '["ROLE_USER"]',
                        'Role Trainer' => '["ROLE_TRAINER"]',
                        'Role Administrator' => '["ROLE_ADMIN"]',
                    ],
                    'mapped' => false,
                    'attr' => [
                        'class' => 'm-2'
                    ],
                    'required' => false,
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Filtruj',
                    'attr' => [
                        'class' => 'm-2'
                    ],
                ],
                )
            ->add(
                'reset',
                SubmitType::class,
                [
                    'label' => 'Resetuj',
                    'attr' => [
                        'class' => 'm-2'
                    ],
                ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'methods' => 'POST',
        ]);
    }

}


?>
