<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CommentForm extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'rows' => 4
                ]
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Post Comment',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }
}