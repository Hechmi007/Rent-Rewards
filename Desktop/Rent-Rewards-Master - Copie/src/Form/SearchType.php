<?php

namespace App\Form;



namespace App\Form;



use Symfony\Component\Form\Extension\Core\Type\SubmitType;



use App\Entity\CharityDemand;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('charityName', TextType::class, [
                'label' => '',
                'required' => false,
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Search',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
