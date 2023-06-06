<?php

namespace App\Form;

use App\Utils\Constants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
  

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "choices"=> [
                "System Admin" => Constants::ROLE_ADMIN,
                "President" => Constants::PRESIDENT,
                 "Vice President" => Constants::VICE_PRESIDENT,
                  "Directorate" => Constants::ROLE_DIRECTORATE,
                   "College Coordinator" => Constants::ROLE_COLLEGECOORDINATOR,
                    "Work Unit" => Constants::ROLE_WORK_UNIT,
                    "Reviewer" => Constants::ROLE_REVIEWER,
            ],
            'mapped' => false, "multiple" => true,
            "placeholder" => "Select Role"
        ]);
    }
  
    public function getParent()
    {

        return ChoiceType::class;
        
    }
}
