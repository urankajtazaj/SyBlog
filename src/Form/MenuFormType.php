<?php

namespace App\Form;

use App\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MenuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $category = $options['em']->getRepository(\App\Entity\Category::class)->findAll();
        
        $builder
            ->add('category', EntityType::class, [
                'class' => \App\Entity\Category::class,
                'attr' => [
                    'class' => 'form-control'
                ],
                'choice_label' => function($category) {
                    return $category->getName();
                },
            ])
            ->add('add', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-blue mt-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'em' => null
        ]);
    }
}
