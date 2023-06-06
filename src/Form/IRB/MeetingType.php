<?php

namespace App\Form\IRB;

use App\Entity\IRB\Application;
use App\Entity\IRB\BoardMember;
use App\Entity\IRB\Meeting;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class MeetingType extends AbstractType
{
   

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', null, [
                "data" =>$options['data']->getNumber()?$options['data']->getNumber(): "JU-" . rand(1000, 100000),
                "attr" => [

                    "readonly" => true
                ],
            
            ])
            ->add('heldAt', DateTimeType::class, [
                "html5" => true,
                "widget" => "single_text",
                // "min" => new \DateTime(),
            ]);
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $meeting = $event->getData();
                $form = $event->getForm();
        
                // checks if the Product object is "new"
                // If no data is passed to the form, the data is "null".
                // This should be considered a new "Product"
                if (!$meeting || null != $meeting->getId()) {
                    $form  ->add('attendee', null, [
                        "expanded" => true,
        
        
        
                    ])
                    ->add('minuteTakenAt', DateTimeType::class, [
                        "html5" => true,
                        "widget" => "single_text",
                        "required" => false,
                        // "min" => new \DateTime(),
                    ])
                    ->add('applications', null, [
                        "expanded" => true,
                        "label" => "",
                        'choice_label' => function (Application $application) {
                            return "" . $application . "==>" . $application->getSubmittedBy() . "";
                        },
        
        
                    ]);
                }
            });
         ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meeting::class,
        ]);
    }
}
