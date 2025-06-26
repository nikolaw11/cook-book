<?php

/**
 * Comment type form.
 */

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CommentTypeForm.
 */
class CommentTypeForm extends AbstractType
{
    /**
     * Defines the form fields and their configuration.
     *
     * @param FormBuilderInterface $builder the form builder
     * @param array                $options options passed to the form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'label.comment_content',
                ]
            )
            ->add(
                'createdAt',
                null,
                [
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'author',
                EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => 'id',
                ]
            )
            ->add(
                'recipe',
                EntityType::class,
                [
                    'class' => Recipe::class,
                    'choice_label' => 'id',
                ]
            );
    }

    /**
     * Configures the default options for this form type.
     *
     * @param OptionsResolver $resolver the resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Comment::class,
            ]
        );
    }
}
