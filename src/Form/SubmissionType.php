<?php

namespace App\Form;

use App\Entity\Submission;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Count;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SubmissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $submission=$options["data"];
        $builder

            ->add('title', TextType::class, ['attr' => []])
            ->add('step', HiddenType::class)
            ->add('sub_title')
            ->add('shortTitle')
            ->add('college'  , EntityType::class, array(
                'placeholder' => '---Select college to apply   ---',

                'class' => 'App\Entity\College',
                'attr' => array(
                    'empty' => 'College',
                    'required' => true,
                    'class' => 'select2 chosen-select form-control',
                ),
                 

            ))
            ->add(
                'abstract',
                TextareaType::class,
                [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]
            )
            // ->add('actionplan',   CKEditorType::class, [
            //     'attr' => [
            //         'placeholder' => 'References',
            //         'class' => 'form-control col col-md-12 col-sm-12 col-lg-9  ',
            //         'required' => false,

            //     ],
            // ])
            // ->add('abstract' ) 
            ->add(
                'background_and_rationale',
                TextareaType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]
            )
            ->add(
                'methodology',
                TextareaType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]
            )
            ->add(
                'research_outcome',
                TextareaType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]
            ) 
            ->add('reference',   CKEditorType::class, [
                'attr' => [
                    'placeholder' => 'References',
                    'class' => 'form-control col col-md-12 col-sm-12 col-lg-9  ',
                    'required' => false,

                ],
            ])

 
            // ->add('budget_and_time_schedule',   CKEditorType::class, [
            //     'attr' => [
            //         'placeholder' => 'Budget and time schedule',
            //         'class' => 'form-control col col-md-12 col-sm-12 col-lg-9  ',
            //         'required' => false,

            //     ],
            // ])
            ->add('proposalFile',VichFileType::class,[
                'allow_delete' => false,
                'required'=>false,
                'label'=>"Proposal Attachement",
                'allow_delete' => true,
                'data_class' => null,
                'empty_data' => '',
               'download_label' => false,
               'row_attr'=>[],
               "attr"=>[
                   "accept"=>"application/msword,
                   application/vnd.openxmlformats-officedocument.wordprocessingml.document"
               ]
            ])
 
            ->add(
                'GeneralObjective',
                TextareaType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]
            )

            ->add('specificObjectives', CollectionType::class, [
                'entry_type' => SpecificObjectiveType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                // 'constraints' => [
                //     new Count([
                //       'min' => 0,
                //       'minMessage' => 'You have to add some  specific objectives to your research details',
                //       // also has max and maxMessage just like the Length constraint
                //     ]),
                //   ],
                'allow_delete' => true,
            ])


            ->add('researchTimeTables', CollectionType::class, [
                'entry_type' => ResearchTimeTableType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'required' => false,

                // 'constraints' => [
                //     new Count([
                //       'min' => 1,
                //       'minMessage' => 'Research Time Tables Must have at least one value',
                //       // also has max and maxMessage just like the Length constraint
                //     ]),
                //   ],
            ])

            ->add('thematic_area', EntityType::class, array(
                'placeholder' => '---Select Thematic Area    ---',

                'class' => 'App\Entity\ThematicArea',
                'attr' => array(
                    'empty' => 'Thematic Area',
                    'required' => true,
                    'class' => 'select2 chosen-select form-control',
                ),
                'query_builder' => function (EntityRepository $entityRepository)use ($submission) {
                   
                    return $entityRepository->createQueryBuilder('t')
                    ->join("t.callForProposal","c")->andWhere("c.id = :call")->setParameter("call",$submission->getCallForProposal()->getId())
     // ->andWhere("u.id = :themeatic")->setParameter("themeatic",$submission->getCallForProposal()->getThematicArea())
                       ;
                }

            ))
            ->add('fundingScheme', EntityType::class, array(
                'placeholder' => '---Select funding scheme    ---',

                'class' => 'App\Entity\FundingScheme',
                'attr' => array(
                    'empty' => 'Funding Scheme',
                    'required' => true,
                    'class' => 'select2 chosen-select form-control',
                ),
                'query_builder' => function (EntityRepository $entityRepository)use ($submission) {
                   
                    return $entityRepository->createQueryBuilder('f')
                    ->join("f.callForProposals","c")->andWhere("c.id = :call")
                    ->setParameter("call",$submission->getCallForProposal()->getId())
                        ;
                }

            ))
            ->add('keywords', null, ["attr" => ["data-role" => "tagsinput"]])
            ->add( 'agree_to_the_terms',
                ChoiceType::class,
                [
                    "label" => "I have read guidelines and agree",
                    "choices" =>  ["I have read guidelines and agree" => "1"],
                    'mapped' => false, 
                    'required' => true, 

                    "multiple" => true, 
                    'expanded' => true,
                ]
            )

            // null,["label"=>"I agree with the Terms and Conditions."])
            ->add('submissionBudgets', CollectionType::class, [
                'entry_type' => SubmissionBudgetType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'required' => true,
                'allow_delete' => true,
                // 'constraints' => [
                //     new Count([
                //       'min' => 0,
                //       'minMessage' => 'You have to add some  value to budget field',
                //       // also has max and maxMessage just like the Length constraint
                //     ]),
                //   ],
            ])
            ->add('submissionAttachements', CollectionType::class, [
                'entry_type' => SubmissionAttachementType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'error_bubbling' => false,
                'allow_delete' => true,
                // 'constraints' => [
                //     new Count([
                //         'min' => 1,
                //         'minMessage' => 'You have to add some  attachment',
                //         // also has max and maxMessage just like the Length constraint
                //     ]),
                // ],
                'required' => false
            ])
            ->add('coAuthors', CollectionType::class, [
                'entry_type' => CoAuthorType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ])

            #  ->add('save', SubmitType::class,[
            # 'attr'=>['class'=>'btn btn-success']
            # ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Submission::class,
        ]);
    }
}
