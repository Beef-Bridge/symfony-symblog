<?php

namespace App\Form;

use App\Entity\Post\Category;
use App\Model\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
            ->add('q', TextType::class, [
                'attr' => [
                    'placeholder' => 'Recherche via un mot clé...'
                ],
                'required' => false,
                'empty_data' => ''
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => Request::METHOD_GET,
            'csrf_protection' => false
        ]);
    } 
}