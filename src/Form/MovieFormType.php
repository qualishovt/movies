<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'block bg-transparent border-gray-400 border-2 w-full p-3 rounded-xl text-xl outline-none mb-10',
                    'placeholder' => 'Enter title...'
                ],
                'label' => false
            ])
            ->add('releaseYear', IntegerType::class, [
                'attr' => [
                    'class' => 'block bg-transparent border-gray-400 border-2 w-full p-3 rounded-xl text-xl outline-none mb-10',
                    'placeholder' => 'Enter release year...'
                ],
                'label' => false
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'block bg-transparent border-gray-400 border-2 w-full p-3 rounded-xl text-xl outline-none mb-10',
                    'placeholder' => 'Enter description...'
                ],
                'label' => false
            ])
            ->add('imagePath', FileType::class, [
                'attr' => [
                    'class' => 'mb-10'
                ],
                'label' => false,
                'required' => false,
                'mapped' => false
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'uppercase mt-15 bg-blue-500 text-gray-100 text-lg font-extrabold py-4 px-8 rounded-3xl'
                ],
                'label' => 'Submit'
            ])
            // ->add('actors')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
