<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\ProductsCategory;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('productname')
            ->add('productscategory', EntityType::class, ['class' => productscategory::class, 'choice_label' => 'Categoryname', 'label' => 'ProductsCategory'])
            ->add('RentPrice')
            ->add('Availabilitydate')
            ->add('ProductType')            
            ->add('ProductPicture', FileType::class, array('data_class' => null), [
                'label' => 'Product Picture (Image file)',
                'mapped' => true,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])

            ->add('ProductAdress')
            ->add('StillAvailable')
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
