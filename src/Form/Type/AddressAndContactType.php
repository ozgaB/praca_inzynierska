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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class AddressAndContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'phoneNumber',
                TextType::class,
                [
                    'label' => 'Phone Number',
                ]
                )
            ->add(
                'country',
                TextType::class,
                [
                    'label' => 'Country',
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                    'label' => 'City',
                ]
            )
            ->add(
                'postCode',
                TextType::class,
                [
                    'label' => 'Post Code',
                ]
            )
            ->add(
                'streetAndNumber',
                TextType::class,
                [
                    'label' => 'Street And Number',
                ]
            )
            ->add(
                'isPublic',
                CheckboxType::class,
                [
                    'label' => 'Is public?',
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
            'data_class' => AddressAndContact::class,
            'methods' => 'POST',
        ]);
    }

}


?>
