<?php

namespace App\Form;

use App\Entity\Animer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AnimerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
            ])
            ->add('demiJournee', ChoiceType::class, [
                'choices' => ['matin' => 0, 'aprem' => 1]
            ])
            ->add('fkAnimerFormation')
            ->add('fkAnimerUser');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Animer::class,
        ]);
    }
}
