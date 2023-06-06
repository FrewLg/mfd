<?php

namespace App\Form\CRP;

use App\Entity\College;
use App\Entity\CRP\CoInvestigator;
use App\Entity\CRP\CollaborativeResearchProject;
use App\Entity\CRP\FundingOrganization;
use App\Entity\CRP\ProjectStatus;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use App\Entity\CRP\ProjectType;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollaborativeResearchProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('shortDescription')
            // ->add('Deliverables')
            ->add('YearOfCemmencement' , DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('EndDate'   , DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('FundingOpportunityName')
            ->add('AmountOfGrant' ,NumberType::class,["attr"=>["min"=>"1"],'required'=>true])
            ->add('Currency')
            ->add('ThematicArea')
            ->add('OtherInsitutes')
            ->add('projectProgress')
            ->add('ResponsiblePrimaryInstitute'
            , null, array(

                'placeholder' => '---Select funding organization  ---',
                "class" => College::class,
                'attr' => array(
                    'empty' => 'FundingOrganization',

                    'class' => 'select2 chosen-select form-control',
                ),
            ))
            ->add('principalInvestigatingOrganization'  , null, array(

                'placeholder' => '---Select Principal organization  ---',
                "class" => FundingOrganization::class,
                'attr' => array(
                    'empty' => 'Thematic Area',

                    'class' => 'select2 chosen-select form-control',
                ),
            ))
            ->add('PrincipalInvestigator' , null, array(

                'placeholder' => '---Select Users  ---',
                "class" => User::class,
                'attr' => array(
                    'empty' => 'Select Users',

                    'class' => 'select2 js-example-responsive chosen-select ',
                    'multiple'=>false,
                    // 'style'=>array('width'=>'75',),
                ),
            ))
            ->add('ProjectType' , null, array(

                'placeholder' => '---Select Project Type  ---',
                "class" => ProjectType::class,
                'attr' => array(
                    'empty' => 'Project type',

                    'class' => 'select2 chosen-select form-control',
                ),
            ))
            ->add('ProjectStatus' , null, array(

                'placeholder' => '---Select project status  ---',
                "class" => ProjectStatus::class,
                'attr' => array(
                    'empty' => 'ProjectStatus',

                    'class' => 'select2 chosen-select form-control',
                ),
            ))
            ->add('CoPrincipalInvestigator'

            , null, array(

                'placeholder' => '---Select Users  ---',
                "class" => User::class,
                'attr' => array(
                    'empty' => 'Thematic Area',

                    'class' => 'select2 chosen-select form-control',
                ),
            ))
            ->add('fundingOrganization' , null, array(

                'placeholder' => '---Select funding organization  ---',
                "class" => FundingOrganization::class,
                'attr' => array(
                    'empty' => 'FundingOrganization',

                    'class' => 'select2 chosen-select form-control',
                ),
            ))
            ->add('coInvestigators' , null, array(

                'placeholder' => '---Select Users  ---',
                "class" => User::class,
                'attr' => array(
                    'empty' => 'Thematic Area',

                    'class' => 'select2 chosen-select form-control',
                ),
            ))
            ->add('deliverables', CollectionType::class, [
                'entry_type' => DeliverablesType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                // 'data_class'=>false,
                'by_reference' => false,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CollaborativeResearchProject::class,
        ]);
    }
}
