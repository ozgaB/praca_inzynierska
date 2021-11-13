<?php
namespace App\Form\Type;

use App\Entity\Security\User;
use App\Entity\TrainerBio\TrainerBio;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
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

class TrainerBioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'shortDescription',
                TextType::class,
                [
                    'label' => 'Short Description',
                ]
            )
            ->add(
                'description',
                CKEditorType::class,
                [
                    'label' => 'Description',
                ]
                )
            ->add(
                'facebookLink',
                TextType::class,
                [
                    'label' => 'Facebook Link',
                ]
            )
            ->add(
                'instagramLink',
                TextType::class,
                [
                    'label' => 'Instagram Link',
                ]
            )
            ->add(
                'isPublic',
                CheckboxType::class,
                [
                    'label' => 'Is public?',
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
            'data_class' => TrainerBio::class,
            'methods' => 'POST',
        ]);
    }

}


?>
