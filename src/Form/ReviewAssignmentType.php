<?php

namespace App\Form;

use App\Entity\ReviewAssignment;
use App\Entity\User;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReviewAssignmentType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $reviewAssignment = $options['data'];
        if (!$reviewAssignment instanceof ReviewAssignment) {
            return;
        }
        $builder
            // ->add('reviewer', EntityType::class, array(
            //     'placeholder' => '---Select reviewer   ---',

            //     'class' => 'App\Entity\User',
            //     'attr' => array(
            //         // 'empty' => 'Reviewers from System',
            //         'required' => true,
            //         'class' => 'select2   ',
            //     ),
            // ))
            ->add('reviewer', EntityType::class, [
                 'placeholder' => '---Select reviewer   ---',

                "class" =>  'App\Entity\User',
                 
            ])

            ->add('reviewfile',  ChoiceType::class, [
                'choices' => [
                 'Original' => 0,
                 'New File' => 1,
                 ],
                 'label'=>'File to be reviewed',
                'expanded'=>true,
                'attr' => [
                        'class' => 'form-horizontal  ',
                      'required' => true,
                    'multiple'=>false,
                'onclick' => 'attachOrDoi();',

                       ] ,            
                    ]) 
            ->add('file_tobe_reviewed', FileType::class, [
                'label' => 'Upload proposal attachment',
                'mapped' => false, 'attr' => [
                    'class' => 'form-control  m-0   ',
                    'required' => true,

                ],
                'required' => true,
            ])

            ->add('invitationDueDate', DateType::class, array(
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day'],
                'label' => 'Invitation response duedate',

                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'min' => (new DateTime('now'))->format('Y-m-d'),
                    'max' => $reviewAssignment->getSubmission()->getCallForProposal()->getReviewProcessEnd()->format('Y-m-d'),

                    'required' => true,
                    'class' => 'form-control',
                ),
            ))
            ->add('duedate', DateType::class, array(
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day'],
                'label' => 'Review duedate',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'min' => (new DateTime('now'))->format('Y-m-d'),
                    'max' => $reviewAssignment->getSubmission()->getCallForProposal()->getReviewProcessEnd()->format('Y-m-d'),
                    'required' => true,
                    'class' => 'form-control',
                ),
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => ReviewAssignment::class,
        ]);
    }
}

class ExternalReviewAssignmentType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $reviewAssignment = $options['data'];
        if (!$reviewAssignment instanceof ReviewAssignment) {
            return;
        }
        $builder

            ->add('external_reviewer_name')
            ->add('middle_name')
            ->add('last_name')

            ->add('reviewfile',  ChoiceType::class, [
                'choices' => [
                 'Original' => 0,
                 'New File' => 1,
                 ],
                 'label'=>'File to be reviewed',
                'expanded'=>true,
                'attr' => [
                        'class' => 'form-group  ',
                      'required' => true,
                    'multiple'=>false,
                'onclick' => 'exernalreviewfilechoose();',

                       ] ,            
                    ]) 

            
            ->add('external_reviewer_email',
                TextType::class, [
                    'attr' => ['class' => 'form-control col col-md-12 col-sm-12 col-lg-12 '],
                ])

            ->add('file_tobe_reviewed', FileType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control   col-md-12 col-sm-12 col-lg-12  ',
                    'required' => true,

                ],
                'required' => true,
            ])

            ->add('invitationDueDate', DateType::class, array(
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day'],
                'label' => 'Invitation response duedate',

                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'min' => (new DateTime('now'))->format('Y-m-d'),
                    'required' => true,
                    'class' => 'form-control',
                ),
            ))
            
            ->add('duedate', DateType::class, array(
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day'],
                'label' => 'Review duedate',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'min' => (new DateTime('now'))->format('Y-m-d'),
                    'max' => $reviewAssignment->getSubmission()->getCallForProposal()->getReviewProcessEnd()->format('Y-m-d'),
                    'required' => true,
                    'class' => 'form-control',
                ),
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => ReviewAssignment::class,
        ]);
    }
}
