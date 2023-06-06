<?php

namespace App\Form\IRB;

use App\Entity\IRB\Application;
use App\Entity\IRB\ProjectType;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('startDate',TextType::class,[
                "attr"=>[
                    "class"=>"daterangerpicker",
                    "autocomplete"=>"off"
                ]
            ])
            ->add('endDate',TextType::class,[
                "attr"=>[
                    "class"=>"daterangerpicker",
                    "autocomplete"=>"off"
                ]
            ])
            ->add('location') 
            ->add('type',ChoiceType::class,[
                'choices'=>["PI of the project"=>1,"Advisor of the project"=>2],
                "placeholder"=>"Select type"
            ])
          
            ->add('submittedAt',TextType::class,[
                "attr"=>[
                    "class"=>"daterangerpicker",
                ]
            ])
          
            ->add('projectType',EntityType::class,[
                "multiple"=>true,
                "class"=>ProjectType::class,
                "attr"=>[
                    "class"=>"select2 col-6"
                ],
            ])
            ->add('submittedBy',EntityType::class,[
                "class"=>User::class,
                "multiple"=>true,
                "attr"=>[
                    "class"=>"select2 col-6"
                ],
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('u')
                    ->leftJoin("App:IRB\Application","s","With","s.submittedBy=u.id")
                       ;
                }
            ])
            // ->add('pi')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => Application::class,
            'required' => false,
            'attr' => array(
                'class' => 'row',
                'autocomplete' => 'off'
            )
        ]);
    }
}
