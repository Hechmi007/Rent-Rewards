<?php

namespace App\Form;

use App\Entity\Facture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('IDproduit')
            ->add('nomproduit')
            ->add('IDClient')
            ->add('prixproduit')
            ->add('TVA')
            ->add('totalfacture')
            ->add('IDLocataire')
            ->add('DateFacture')
            ->add('Adressfacture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
