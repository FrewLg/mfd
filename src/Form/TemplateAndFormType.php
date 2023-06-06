<?php

namespace App\Form;

use App\Entity\TemplateAndForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateAndFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('fileName', FileType::class, [
                "mapped" => false
            ])
            ->add('type', ChoiceType::class, [
                "choices" => [
                    "IRB" => TemplateAndForm::TYPE_IRB,
                    "Research" => TemplateAndForm::TYPE_RESEARCH,
                ]
            ])
            ->add('description')
            // ->add('callFor')
            // ->add('fileType')
            // ->add('isActive')
            // ->add('college')
            // ->add('createdAt')
            // ->add('uploadedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TemplateAndForm::class,
        ]);
    }
}
