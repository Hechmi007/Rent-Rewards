<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [
                'attr' => [
                    'class' => 'form-control col-6 ',
                    'id' => 'title',
                    'placeholder' => 'Enter title',
                ]
            ])
            ->add('content',TextareaType::class, [
                'attr' => [
                    'class' => 'form-control-resize col-6 mb-6',
                    'id' => 'title',
                    'placeholder' => 'Enter title',
                    
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'required' => false, // image is not required
            ])
            
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
