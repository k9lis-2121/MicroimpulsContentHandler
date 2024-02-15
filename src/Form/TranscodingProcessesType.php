<?php

namespace App\Form;

use App\Entity\TranscodingProcesses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranscodingProcessesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FilmName')
            ->add('StartTime')
            ->add('EndTime')
            ->add('Status')
            ->add('PID')
            ->add('Progress')
            ->add('CtreatedAt')
            ->add('UpdateAt')
            ->add('TranscodingSettings')
            ->add('UserSubmittedBy')
            ->add('ErrorMessage')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TranscodingProcesses::class,
        ]);
    }
}
