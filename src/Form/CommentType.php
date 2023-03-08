<?php

namespace App\Form;

<<<<<<< HEAD:src/Form/CommentType.php
use App\Entity\Comment;
=======
use App\Entity\ProductsCategory;
>>>>>>> main:src/Form/ProductsCategoryType.php
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

<<<<<<< HEAD:src/Form/CommentType.php
class CommentType extends AbstractType
=======
class ProductsCategoryType extends AbstractType
>>>>>>> main:src/Form/ProductsCategoryType.php
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD:src/Form/CommentType.php
            ->add('contentcomment')
            
=======
            ->add('Categoryname')
            ->add('Type')
>>>>>>> main:src/Form/ProductsCategoryType.php
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
<<<<<<< HEAD:src/Form/CommentType.php
            'data_class' => Comment::class,
=======
            'data_class' => ProductsCategory::class,
>>>>>>> main:src/Form/ProductsCategoryType.php
        ]);
    }
}
