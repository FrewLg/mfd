<?php

namespace App\Form;

use App\Entity\IRB\IrbReviewAtachement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IrbReviewAtachementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('attachmentName')

             ->add('attachement', FileType::class, [
                'label' => 'Assessment Guideline  file',
                'attr'=>[
                    'class' => 'form-control col col-md-12 col-sm-12 col-lg-9  ',
                             'required' => true,
            
            ],
                'mapped' => false, 
                'required' => true,
               
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IrbReviewAtachement::class,
        ]);
    }
}
