<?php

namespace App\Form;

use App\Entity\ResearchReport;
use App\Entity\ResearchReportChallengesCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResearchReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('file', FileType::class, [
                "label" => "Progress report",
                "help" => "Upload Progress report",
                "mapped" => false,
                "attr" => [
                    "accept" => "application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                    text/plain, application/pdf, image/*",
                    "class" => "form-control",
                ]
            ])
            ->add('financial_clearance', FileType::class, [
                "label" => "Financial clearance",
                "help" => "Upload Financial clearance",
               
                "mapped" => false,
                "attr" => [
                    "accept" => "application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                    text/plain, application/pdf, image/*",
                    "class" => "form-control",
                ]

            ])

            ->add('researchReportChallenges', CollectionType::class, [
                "entry_type" => ResearchReportChallengeType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => true,
                //    'delete_empty' => function (ResearchReportChallengesCategory $user = null) {
                //     return null === $user || empty($user->getName());
                // },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ResearchReport::class,
        ]);
    }
}
