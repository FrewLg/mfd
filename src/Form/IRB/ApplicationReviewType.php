<?php

namespace App\Form\IRB;


use App\Entity\IRB\ApplicationReview as IRBApplicationReview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('review')
            ->add('checked',null,["label"=>false,"attr"=>["class"=>"col-5"]])
           

            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IRBApplicationReview::class ,
        ]);
    }
}
