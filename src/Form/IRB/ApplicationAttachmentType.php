<?php

namespace App\Form\IRB;

use App\Entity\IRB\ApplicationAttachment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ApplicationAttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('uploadFile',VichFileType::class,[
            'allow_delete' => false,
            'label'=>false,
            "label_attr"=>["class"=>"d-none"],

           'download_label' => 'Download file',
        ])
            ->add('checked',null,["label"=>false,"attr"=>["class"=>"col-5"]])
            ->add('type',null,["label"=>false,"attr"=>["class"=>"col-5"]])

           

            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApplicationAttachment::class,
        ]);
    }
}
