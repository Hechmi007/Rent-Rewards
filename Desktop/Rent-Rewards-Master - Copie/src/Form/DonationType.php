<?php

namespace App\Form;

use App\Entity\CharityDemand;
use Symfony\Component\Form\Extension\Core\Type\SearchType as SearchFormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Donation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class DonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Pointsdonated')
            ->add('datedonation')
           /*   ->add('username', EntityType::class, ['class' => User::class, 'choice_label' => 'username', 'label' => 'username']) */
            ->add('title', EntityType::class, ['class' => CharityDemand::class, 'choice_label' => 'title', 'label' => 'tilte']) 
            /* ->add('phone', TextType::class, [
                'label' => 'Please Enter Your Phone Number',
                'attr' => [
                    'class' => 'form-control'
                ]
                ]) */;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donation::class,
        ]);
    }
}
