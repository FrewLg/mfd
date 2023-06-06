<?php

namespace App\Form;

use App\Entity\CallForProposal;
use App\Entity\FundingScheme;
use App\Entity\ThematicArea;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

// use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface

class CallForProposalType extends AbstractType {

    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $user = $this->security->getUser();
        $college = $user->getUserInfo()->getCollege();

        $builder

            ->add('research_type', ResearchType::class)

            ->add('subject', CKEditorType::class, [
                'attr' => [
                    'placeholder' => 'Body of the call',
                    'class' => 'form-control  col col-md-12 col-sm-12 col-lg-9  ',
                    'required' => false,

                ],
            ])
            ->add('heading', CKEditorType::class, [
                'attr' => [
                    'placeholder' => 'Heading title of the call',
                    'class' => 'form-control col col-md-12 col-sm-12 col-lg-9  ',
                    'required' => false,

                ],
            ])

            ->add('thematicArea' , null, array(

                'placeholder' => '---Select funding organization  ---',
                "class" => ThematicArea::class,
                'attr' => array(
                    'empty' => 'FundingOrganization',

                    'class' => 'select2 chosen-select form-control',
                ),
            ))

            ->add('coPoConfirmation')
            ->add('fundingScheme', null, array(

                'placeholder' => '---Select funding scheme  ---',
                "class" => FundingScheme::class,
                'attr' => array(
                    'empty' => 'fundingScheme', 
                    'class' => 'select2 chosen-select form-control',
                ),
            ))

            ->add('guidelines', CKEditorType::class, [
                'attr' => [
                    'placeholder' => 'Guideline details',
                    'class' => 'form-control  col col-md-12 col-sm-12 col-lg-9  ',
                    'required' => false,

                ],
            ])
            ->add('deadline',  null,['widget' => 'single_text',])

            ->add('funding_source', TextType::class, [
                'attr' => ['class' => 'form-control col col-md-12 col-sm-12 col-lg-9 '],
            ])

            ->add('review_process_start', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('review_process_end', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('reviewers_decision_will_be_communicated_at', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('project_starts_on', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))

            ->add('allow_non_academic_staff_as_pi')
            ->add('allow_pi_from_other_university')
            ->add('commitment_from_other_research')
            ->add('is_call_from_center')
            ->add('attachement', FileType::class, [
                'label' => 'Upload   attachment',
                'mapped' => false, 'attr' => [
                    'class' => 'form-control  m-0   ',
                    'required' => false,

                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => CallForProposal::class,
        ]);
    }
}
