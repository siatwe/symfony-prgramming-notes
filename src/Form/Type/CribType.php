<?php

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CribType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('project')
            ->add('date')
            ->add('editDate')
            ->add('submit', SubmitType::class, ['label' => 'save'])
        ;

        $builder->add(
            'cribContent',
            CollectionType::class,
            [
                'entry_type'    => CribContentType::class,
                'entry_options' => ['label' => false],
                'allow_add'     => true,
                'allow_delete'  => true,
            ]
        );
    }
}
