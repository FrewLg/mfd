<?php

namespace App\Form\IRB;

use App\Entity\IRB\IRBReviewAssignment;
use App\Entity\User;
use App\Repository\IRB\IRBReviewAssignmentRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IRBReviewAssignmentType extends AbstractType {
    private $iRBReviewAssignmentRepository;
    public function __construct(IRBReviewAssignmentRepository $iRBReviewAssignmentRepository) {
        $this->iRBReviewAssignmentRepository = $iRBReviewAssignmentRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $reviewAssignment = $options['data'];
        if (!$reviewAssignment instanceof IRBReviewAssignment) {
            return;
        }

        $already_assigned = (new ArrayCollection($this->iRBReviewAssignmentRepository->findBy(['application' => $options['application'], "token" => null])))->map(function ($element) {
            return $element->getIrbreviewer();
        });

        $builder
            ->add(
                'irbreviewer',
                EntityType::class,
                [
                    "required" => false,
                    'class' => User::class,

                    'query_builder' => function (EntityRepository $er) use ($already_assigned) {

                        $qb = $er->createQueryBuilder('u')
                            ->andWhere("u.roles like '%ROLE_BOARD_MEMBER%'");
                        if (sizeof($already_assigned->getValues()) > 0) {
                            $qb->andWhere("u not in  (:irbreviewer)")
                                ->setParameter('irbreviewer', $already_assigned->getValues());
                        }

                        return $qb->orderBy('u.username', 'ASC');
                    },
                    'label' => 'Reveiwer',
                    "attr" => [
                        "class" => "select2 col-3",
                    ],
                    'choice_label' => function (User $user) {
                        return $user . "-(" . count($user->getIRBReviewAssignments()) . ")";
                    },

                ]
            )

            ->add('duedate', DateType::class, array(
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'label' => 'Review due date(default date 10 days from now)',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => (new DateTime('+10 day')),
                'attr' => array(
                    'min' => (new DateTime())->format('Y-m-d'),
                    'required' => true,
                    'class' => 'form-control',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => IRBReviewAssignment::class,
            'application' => null,
        ]);
    }
}

class ExternalIRBReviewAssignmentType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $reviewAssignment = $options['data'];

        if (!$reviewAssignment instanceof IRBReviewAssignment) {
            return;
        }
        $builder

            ->add('external_irbreviewer_name', TextType::class, [
                "label" => "Full name",
            ])

            ->add(
                'external_irbreviewer_email',
                null,
                [
                    'attr' => ['class' => 'form-control col col-md-12 col-sm-12 col-lg-9 '],
                ]
            )

            ->add('duedate', DateType::class, array(
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'label' => 'Review duedate',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new DateTime('+10 day'),
                'attr' => array(
                    'min' => (new DateTime('now'))->format('Y-m-d'),
                    // 'max' => (new DateTime('now'))->format('Y-m-d'),
                    'required' => true,
                    'class' => 'form-control',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => IRBReviewAssignment::class,
        ]);
    }
}
