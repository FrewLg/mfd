<?php

namespace App\Form\CRP;

use App\Entity\CRP\ProjectAttachment;
use App\Entity\CRP\ProjectAttachmentType as CRPProjectAttachmentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectAttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('name')
            ->add('attachmentType' 
            , null, array(

                'placeholder' => '-- Select attachment type  --',
                "class" => CRPProjectAttachmentType::class,
                'attr' => array(
                    'empty' => 'AttachmentType', 
                    'class' => 'select2 chosen-select form-control',
                ),
            ))
            ->add('file' , FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                "attr" => [
                    "accept" => "image/*",
                    "class" => "form-control",

                ],
            ])
            ->add('description')
            // ->add('dataAttached')
            // ->add('dateUpdated')
            // ->add('project')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectAttachment::class,
        ]);
    }
}
