<?php

namespace App\Form;

use App\Entity\SubmissionAttachement;
use App\Entity\AttachementType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SubmissionAttachementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile',VichFileType::class,[
                'allow_delete' => true
                ,
                'delete_label' => 'Remove file',
           //     'download_uri' => '...',
               'download_label' => 'Download file',
            ])
            ->add('name'
            , null, array(

                'placeholder' => '---Select attachement type  ---',
                "class" => AttachementType::class,
                'attr' => array(
                    'empty' => 'Select  ',

                    'class' => 'select2 js-example-responsive chosen-select ',
                    'multiple'=>false,
                    'required'=>true,
                    // 'style'=>array('width'=>'75',),
                ),
            ))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubmissionAttachement::class,
        ]);
    }
}
