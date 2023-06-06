<?php

namespace App\Form\IRB;

use App\Entity\IRB\Revision;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class RevisionType extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $entityManagerInterface) {
        $this->em = $entityManagerInterface;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
   
        ->add('revisionAttachments',CollectionType::class,[
            'entry_type' => RevisionAttachmentType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'by_reference' => false,
            'error_bubbling'=>false,
            'allow_delete' => true,
            'required'=>false,
        ])
       
      

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Revision::class,
        ]);
    }
}
