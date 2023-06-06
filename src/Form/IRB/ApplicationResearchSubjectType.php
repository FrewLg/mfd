<?php

namespace App\Form\IRB;

use App\Entity\Application;
use App\Entity\IRB\ApplicationResearchSubject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationResearchSubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('checked',null,["label"=>false])
            ->add('subject')
            ->add('other')

            ->add('number',null,["label"=>"#"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApplicationResearchSubject::class,
        ]);
    }
}
