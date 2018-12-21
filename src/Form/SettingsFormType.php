<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('logo', FileType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'd-block mb-3'
                ]
            ])
            ->add('page_name', TextType::class, [
                'label' => 'Page Name',
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('tagline', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('phone_1', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('phone_2', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-blue mt-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Settings::class
        ]);
    }
}
