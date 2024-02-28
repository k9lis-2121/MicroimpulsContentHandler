<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VodCustomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('kp_id', IntegerType::class, [
                'label' => 'КИНОПОИСК ID',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('content_type', CheckboxType::class, [
                'required'   => false,
                'label' => 'Является сериалом?',
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('season_count', IntegerType::class, [
                'required'   => false,
                'label' => 'Количество сезонов',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('content_name', TextType::class, [
                'label' => 'Название',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('torrent_link', TextType::class, [
                'label' => 'Ссылка на раздачу',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('transcoded', ChoiceType::class, [
                'label' => 'Статус транскода',
                'choices' => [
                    'В очереди' => 'queued',
                    'Транскодируется' => 'transcoding',
                    'Готов' => 'ready',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('moderation', CheckboxType::class, [
                'required'   => false,
                'label' => 'Прошел модерацию',
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required'   => false,
                'label' => 'Комментарий',
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}