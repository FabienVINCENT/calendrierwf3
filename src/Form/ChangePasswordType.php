<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
            'mapped' => false,
            'label' => 'Mot de passe actuel:',
            'invalid_message' => 'Mot de passe incorrect'
            ])

            ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => ['label' => 'Nouveau mot de passe:'],
            'second_options' => ['label' => 'Confirmer le mot de passe:'],
            'invalid_message' => 'Les deux champs doivent être identiques'])  ;

            // ->add('submit', SubmitType::class, [
            // 'attr' => [
            // 'class' => 'btn btn-primary btn-block w-25'],
            // 'label' => 'Modifier']);               
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
