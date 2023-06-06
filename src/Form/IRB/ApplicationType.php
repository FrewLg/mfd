<?php

namespace App\Form\IRB;

use App\Entity\IRB\Application;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ApplicationType extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $entityManagerInterface) {
        $this->em = $entityManagerInterface;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type',ChoiceType::class,[
                'choices'=>["PI of the project"=>1,"Advisor of student thesis/dissertation"=>2],
                'expanded'=>true
            ])
            ->add('title')
            ->add('applicationType')
            ->add('pi' , null, array(
                'required'=>false,
                                 
                         'attr' => array(
                             'empty' => 'Select PI ',
                             'required' => true,
                             'class' => 'form-control select2 chosen-select ',
                         )
                     ))
            ->add('startDate',null,[   'widget' => 'single_text',])
            ->add('endDate',null,[   'widget' => 'single_text',])
            ->add('location')
            ->add('description')
            ->add('projectType');
          
           
           $builder->add('applicationResearchSubjects',CollectionType::class,[
            'entry_type' => ApplicationResearchSubjectType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'by_reference' => false,
            'error_bubbling'=>false,
            'allow_delete' => true,
            'required'=>false,
        ])
        ->add('applicationMitigationStrategies',CollectionType::class,[
            'entry_type' => ApplicationMitigationStrategyType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'by_reference' => false,
            'error_bubbling'=>false,
            'allow_delete' => true,
            'required'=>false,
        ])
        // ->add('applicationReviews',CollectionType::class,[
        //     'entry_type' => ApplicationReviewType::class,
        //     'entry_options' => ['label' => false],
        //     'allow_add' => true,
        //     'by_reference' => false,
        //     'error_bubbling'=>false,
        //     'allow_delete' => true,
        //     'required'=>false,
        // ])
        ->add('applicationAttachments',CollectionType::class,[
            'entry_type' => ApplicationAttachmentType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'by_reference' => false,
            'error_bubbling'=>false,
            'allow_delete' => true,
            'required'=>false,
        ])
        ->add('uploadFile',VichFileType::class,[
            'allow_delete' => false,

           'download_label' => 'Download file',
        ])
        ->add('members', CollectionType::class, [
            'entry_type' => CoAuthorType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
        ])
        ->add('college', EntityType::class, [
  'placeholder' => '-- Select College/ Institute --',
            'class' => \App\Entity\College::class,
            'attr' => ['label' => 'Application  to Institute/College'
        ,'class'=>'select2 chosen-select form-control'
        ],
            
        ])
      

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
