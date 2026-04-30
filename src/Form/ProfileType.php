<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compagnyName', TextType::class, [
                'label' => 'Raison Sociale',
                'constraints' => [new NotBlank()],
                'help' => 'Le nom de votre entreprise qui apparaîtra sur vos factures.',
            ])
            ->add('siret', TextType::class, [
                'label' => 'Numéro SIRET (Optionnel)',
                'required' => false,
            ])
            ->add('iban', TextType::class, [
                'label' => 'IBAN',
                'required' => false,
                'help' => 'Compte bancaire qui recevra les virements de vos clients.',
            ])
            ->add('cgv', TextareaType::class, [
                'label' => 'Conditions Générales de Vente (CGV)',
                'required' => false,
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Ces conditions apparaîtront en bas de vos factures PDF...',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
