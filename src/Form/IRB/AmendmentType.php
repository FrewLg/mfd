<?php

namespace App\Form\IRB;

use App\Entity\IRB\Amendment;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmendmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('purpose',CKEditorType::class,['config'=>['toolbar'=>'standard']])
            ->add('changes',CKEditorType::class,['config'=>['toolbar'=>'standard']])
            ->add('recruitment',CKEditorType::class,['config'=>['toolbar'=>'standard']])
            ->add('procedures',CKEditorType::class,['config'=>['toolbar'=>'standard']])
            ->add('attachment',FileType::class,['mapped'=>false,'multiple'=>true,"attr"=>["class"=>"form-control"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Amendment::class,
        ]);
    }
}
