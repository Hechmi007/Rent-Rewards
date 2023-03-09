<?php

namespace App\Form;

use App\Entity\AchatPacks;
use App\Entity\FidelityCard;
use App\Entity\Paquet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AchatPacksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Date')
            ->add('NamePaquet',EntityType::class,['class' => Paquet::class, 'choice_label' => 'NomPacks', 'label' => 'NomPacks'])
            ->add('Numcarte',EntityType::class,['class' => FidelityCard::class, 'choice_label' => 'numcarte', 'label' => 'numcarte'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AchatPacks::class,
        ]);
    }
}
