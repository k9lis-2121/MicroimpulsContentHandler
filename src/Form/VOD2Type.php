<?php

namespace App\Form;

use App\Entity\VOD;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VOD2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('kp_id')
            ->add('content_type')
            ->add('season_count')
            ->add('content_name')
            ->add('torrent_link')
            ->add('transcoded')
            ->add('moderation')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VOD::class,
        ]);
    }
}
