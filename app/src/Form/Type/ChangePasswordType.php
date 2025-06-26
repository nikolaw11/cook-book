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
                    'label' => 'label.current_password',
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'message.current-password'],
                    'constraints' => [
                        new NotBlank(['message' => 'message.current_password']),
                        new UserPassword(['message' => 'message.current_password_invalid']),
                    ],
                ]
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'label' => 'label.new_password',
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'message.new-password'],
                    'constraints' => [
                        new NotBlank(['message' => 'message.new_password']),
                        new Length(
                            [
                                'min' => 6,
                                'minMessage' => 'message.password_limit',
                                'max' => 4096,
                            ]
                        ),
                    ],
                ]
            );
    }
}
