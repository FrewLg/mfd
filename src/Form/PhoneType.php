<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneType extends AbstractType
{
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "attr"=>[
               "pattern"=>"(\+251|09)[0-9]{8}"
            ]
        ]);
    }
    public function getParent()
    {
 

        return TextType::class;
    }
}
