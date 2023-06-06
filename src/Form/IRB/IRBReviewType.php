<?php

namespace App\Form\IRB;

use App\Entity\IRB\IRBReview;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IRBReviewType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('remark', ChoiceType::class, [
                'label' => 'IRB Decision',
                'placeholder' => '--Select Editorial decision--',
                'choices' => [
                    'Accepted' => 3,
                    'Accepted with condition' => 2,
                    'Declined' => 1,

                ],
                'attr' => [
                    'class' => 'form-control',
                    'required' => true,
                ],
            ])
            ->add('comment', CKEditorType::class, [
                'label' => 'Comments and feedbacks regarding with the decision',
                'attr' => ['placeholder' => 'Comments ',
                    'class' => 'form-control col col-md-12 col-sm-12 col-lg-9  ',
                    'required' => false,

                ]])

            // ->add('attachment', FileType::class, [
            //     'label' => '  Report        file',
            //     'mapped' => false,
            //     'required' => true,
            //     'attr' => [

            //         'class' => 'form-control',

            //         'required' => true,

            //     ],
            // ])

            // ->add('evaluation_attachment', FileType::class, [
            //     'label' => 'Grading    file',
            //     'mapped' => false,
            //     'required' => true,
            //     'attr' => [

            //         'class' => 'form-control',

            //         'required' => true,

            //     ],
            // ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => IRBReview::class,
        ]);
    }
}
