<?php

/**
 * Admin change user password type.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class AdminChangeUserPasswordType.
 */
class AdminChangeUserPasswordType extends AbstractType
{
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options array
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Nowe hasło'],
                    'second_options' => ['label' => 'Powtórz hasło'],
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank(['message' => 'Wprowadź hasło']),
                        new Length(['min' => 6, 'minMessage' => 'Hasło musi mieć przynajmniej {{ limit }} znaków']),
                    ],
                ]
            );
    }
}
