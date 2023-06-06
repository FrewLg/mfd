<?php

namespace App\Form\CRP;

use App\Entity\CRP\FundingOrganization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FundingOrganizationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('logo' , FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                "attr" => [
                    "accept" => "image/*",
                    "class" => "form-control",

                ],
            ])
            ->add('acronym')
            ->add('acronymColor')
            ->add('detailsAboutOrg')
            // ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FundingOrganization::class,
        ]);
    }
}
