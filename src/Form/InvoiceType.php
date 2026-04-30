<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Form\InvoiceItemType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => \App\Entity\Client::class,
                'choice_label' => 'lastName',
                'placeholder' => 'Choisir un client',
            ])
            ->add('dueDate', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label'  => "Date d'échéance",
            ])
            ->add('invoiceItems', CollectionType::class, [
                'entry_type'   => InvoiceItemType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype'    => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
