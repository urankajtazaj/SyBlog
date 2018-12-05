<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PostForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $category = $options['cats']->getRepository(Category::class)->findAll();
        
        // dd($category);

        $builder
            ->add('cover', FileType::class, [
                'label' => 'Cover image',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('delete_cover', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'rows' => 20
                ]
            ])
            ->add('category', EntityType::class, [
                // 'label' => ' ',
                // 'choices' => $category,
                'class' => Category::class,
                // 'multiple' => true,
                // 'expanded' => true,
                // 'mapped' => false,
                'choice_label' => function($category) {
                    return $category->getName();
                },
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'cats' => null
        ]);
    }

}