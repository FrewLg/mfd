<?php

namespace App\Form\IRB;

use App\Entity\IRB\BoardMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('user', null, [
                "placeholder" => "Select User",
                "attr" => [
                    "class" => "select2  form-control col-2"
                ]
            ])
        
            ->add('role', ChoiceType::class, [
                "placeholder" => "Select Role",
              
                "required" => true,
                "choices" => [
                    "Chair" => BoardMember::ROLE_CHAIR,
                    "Vice Chair" => BoardMember::ROLE_VICE_CHAIR,
                    "Secretary" => BoardMember::ROLE_SECRETARY,
                    "Coordinator" => BoardMember::ROLE_COORDINATOR, 
                    "Member" => BoardMember::ROLE_MEMBER,
                ],
                "attr" => [
                    "class" => "select2 form-control col-4"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BoardMember::class,
        ]);
    }
}
