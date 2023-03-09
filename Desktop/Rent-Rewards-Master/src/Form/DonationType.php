<?php

namespace App\Form;

use App\Entity\CharityDemand;
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
            ->add('username', EntityType::class, ['class' => User::class, 'choice_label' => 'username', 'label' => 'username'])
            ->add('title', EntityType::class, ['class' => CharityDemand::class, 'choice_label' => 'title', 'label' => 'tilte']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donation::class,
        ]);
    }
}
