<?php

namespace App\Form;

use App\Entity\InvoiceItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('description', null, [
            'label' => false,
            'attr' => ['placeholder' => 'Sélectionner un produit ou taper une description', 'class' => 'form-control']
        ])
        ->add('quantity', NumberType::class, [
            'label' => false,
            'attr' => ['class' => 'form-control qty-input', 'min' => 1]
        ])
        ->add('unitPrice', NumberType::class, [
            'label' => false,
            'attr' => ['class' => 'form-control price-input', 'placeholder' => '0.00']
        ])
        ->add('taxTva', NumberType::class, [
            'label' => false,
            'attr' => ['class' => 'form-control', 'placeholder' => '20']
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceItem::class,
        ]);
    }
}
