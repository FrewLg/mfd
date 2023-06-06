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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder

            ->add('roles', RoleType::class)


            ->add('midle_name')
            ->add('last_name')
            ->add('first_name')
            ->add('gender', ChoiceType::class, [
                "choices" => ["Male" => "Male", "Female" => "Female"],  "placeholder" => "Select Gender"
            ])

            ->add('affiliation')
            ->add('suffix')
            ->add('phoneNumber', PhoneType::class)
            ->add(
                'bio',
                TextareaType::class,
                [
                    'attr' => [
                        'class' => 'form-control col col-md-3',
                        'required' => false,
                    ]
                ]
            )
            ->add('birth_date',DateType::class,[
                "widget"=>"single_text"
            ])
            ->add('username',TextType::class,[
                "mapped"=>false,
                "attr"=>[

                ],
                'constraints' => [new Length(['min' => 5])],
            ])
            // ->add('address')
            // ->add('college')


            // ->add('department')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
        ]);
    }
} 