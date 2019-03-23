<?php

namespace App\Form\Type;

use App\Entity\CribContent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CribContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('language', null, ['required' => false])
            ->add('comment', null, ['required' => false])
            ->add('code', null, ['required' => false])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CribContent::class,
            ]
        );
    }
}
