<?php

namespace App\Form\IRB;

use App\Entity\IRB\ApplicationMitigationStrategy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationMitigationStrategyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('strategy')
            ->add('checked',null,["label"=>false,"attr"=>["class"=>"col-5"]])
            ->add('description',TextareaType::class)
           

            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApplicationMitigationStrategy::class,
        ]);
    }
}
