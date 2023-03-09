<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use App\Entity\FidelityCard;
use App\Entity\Paquet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaquetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomPacks')
            ->add('Discribtion')
            ->add('DateDebutPacks')
            ->add('DateFinPacks')
            ->add('EtatPacks')
            ->add('TypePacks')
            ->add('Numcarte',EntityType::class,['class' => FidelityCard::class, 'choice_label' => 'Numcarte', 'label' => 'FidelityCard'])
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
            'data_class' => Paquet::class,
        ]);
    }
}
