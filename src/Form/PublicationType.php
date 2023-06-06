<?php

namespace App\Form;

use App\Entity\Publication;
use DateTime;
 use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title' , TextType::class,[ 'label'=>"Title ",
            
            'attr'=>[ 
            'placeholder'=>'Title',
            
            'class'=>'form-control mb-0'

            ]])
            ->add('doi' , TextType::class,[ 'label'=>"DOI ",
            'required' => false,
            
            'attr'=>[ 
            'placeholder'=>'DOI',
            'required' => false,
            
            'class'=>'form-control mb-0'

            ]])
            ->add('journal_name' , TextType::class,[ 'label'=>"Journal name  ",
            
            'attr'=>[ 
            'placeholder'=>'Journal name ',
            
            'class'=>'form-control mb-0'

            ]])
            ->add('impact_factor' , TextType::class,[ 'label'=>"Impact factor  ",
            
            'attr'=>[ 
            'placeholder'=>'Impact factor ',
            
            'class'=>'form-control mb-0'

            ]])
            ->add('citation_score', TextType::class,[ 'label'=>"Citation score  ",
            
            'attr'=>[ 
            
            'placeholder'=>'Citation score ',
            
            'class'=>'form-control mb-0'

            ]])
            ->add('member_role')

            ->add('published_at'
            , DateType::class, array(
                'placeholder' => [
        'year' => 'Year', 'month' => 'Month', 'day' => 'Day', ],
        'label' => 'published at',
             
        'widget' => 'single_text',
              'format' => 'yyyy-MM-dd',
                 'attr' => array(
                    'max'=>(new DateTime('now'))->format('Y-m-d'),
                    //  'min' => new \DateTime('-7 year'),
                    'required' => true, 
        'class'=>'form-control',
        )              
          ))
          
            ->add('article_document' , FileType::class, [
                'label' => ' Upload article document ', 
                    'mapped' => false,  'attr'=>[
                        'class' => 'form-control  m-0   ',
                                 'required' => false,
                
                ],
                'required' => false,
                ])
            // ->add('author')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
