<?php

/**
 * Change Password type.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ChangePasswordType.
 */
class ChangePasswordType extends AbstractType
{
    /**
     * Builds the form fields for this form type.
     *
     * This method is used to define the structure of the form, including its fields,
     * field types, options, and validation rules.
     *
     * @param FormBuilderInterface $builder the form builder used to add form fields
     * @param array                $options an array of options passed to the form type
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'currentPassword',
                PasswordType::class,
                [
                    'label' => 'Obecne hasło',
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'current-password'],
                    'constraints' => [
                        new NotBlank(['message' => 'Wprowadź obecne hasło']),
                        new UserPassword(['message' => 'Obecne hasło jest nieprawidłowe']),
                    ],
                ]
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'label' => 'Nowe hasło',
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank(['message' => 'Wprowadź nowe hasło']),
                        new Length(
                            [
                                'min' => 6,
                                'minMessage' => 'Hasło musi mieć co najmniej {{ limit }} znaków',
                                'max' => 4096,
                            ]
                        ),
                    ],
                ]
            );
    }
}
