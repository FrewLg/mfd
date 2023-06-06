<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserInfo;
use App\Entity\MemberRole;
use App\Utils\Constants;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
 

class UserCoAuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
 
            ->add('email' ,EmailType::class,[
            
                "required"=>true,
                "attr"=>[

                ], 
            ])
            ->add('first_name',TextType::class,[
                "mapped"=>false,
                "required"=>true,
                "attr"=>[

                ], 
            ])
            ->add('midle_name' ,TextType::class,[
                "required"=>true,
                "mapped"=>false,
                "attr"=>[

                ], 
            ])
            ->add('last_name' ,TextType::class,[
                "mapped"=>false,
                "required"=>true,
                "attr"=>[

                ], 
            ])
           
            // ->add('department'  , EntityType::class, array(
            //     'required'=>false,
            //     'placeholder' => '-- Select Department --',
            //     "mapped"=>false,
            //     'class' => 'App\Entity\Department',
            //              'attr' => array(
            //                  'empty' => 'Select Department ',
            //                   'required' => true,
            //                  'class' => 'elect2',
            //              )
            //          ))
            // ->add('username',TextType::class,[
            //     "mapped"=>false,
            //     "attr"=>[

            //     ],
            //     'constraints' => [new Length(['min' => 5])],
            // ])
            // ->add('role'    , EntityType::class, array(
            //     'required'=>false,
            //     'placeholder' => '-- Select Role --',
            //     "mapped"=>false,
            //     'class' => 'App\Entity\MemberRole',
            //              'attr' => array(
            //                  'empty' => 'Select Role ',
            //                   'required' => true,
            //                  'class' => 'form-control   chosen-select ',
            //              )
            //          ))
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
