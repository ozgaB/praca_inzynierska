<?php
namespace App\Form\Type;

use App\Entity\Security\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'register_form.fields._submit',
                ]
                )
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => 'register_form.fields._submit',
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'register_form.fields._submit',
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                ]
                )
            ->add(
                'role',
                CheckboxType::class,
                [
                    'label' => 'jestem trenerem',
                    'mapped' => false,
                    'required' => false,
                ]
                )    
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'register_form.fields._submit',
                ]
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'methods' => 'POST',
        ]);
    }

}


?>