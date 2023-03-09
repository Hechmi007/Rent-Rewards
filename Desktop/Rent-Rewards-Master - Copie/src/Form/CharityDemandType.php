<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use App\Form\SubmitType;
use App\Entity\Charitycategory;
use App\Entity\CharityDemand;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CharityDemandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('title')
            ->add('receiver')
            ->add('pointsdemanded')
            ->add('datedemand')
      /*       ->add('username', EntityType::class, ['class' => User::class, 'choice_label' => 'username', 'label' => 'username']) */
            ->add('category', EntityType::class, ['class' => Charitycategory::class, 'choice_label' => 'Type', 'label' => 'CategorieCharity'])
             ->add('FileUpload', FileType::class,  [
                'label' => 'FileUpload (Image file)',
                'mapped' => false,
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
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CharityDemand::class,
        ]);
    }
}
