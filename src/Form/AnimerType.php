<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Animer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Security;

class AnimerType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
                'label' => 'Date'
            ])
            ->add('typeJournee', ChoiceType::class, [
                'choices' => $this->getChoices(),
                'label' => 'Type de journée'
            ])
            ->add('fkAnimerFormation', null, [
                'placeholder' => 'Choisir une formation - ville',
                'label' => 'Formation - Ville'
            ]);

        // Si role ADMIN alors on affiche la liste des user en ROLE_FORMATEUR
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('fkAnimerUser', EntityType::class, [
                'placeholder' => 'Choisir un(e) formateur(trice)',
                'attr' => ['class' => 'js-select2-formateur'],
                // 'placeholder' => 'Choisir un(e) formateur(rice)',
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :roles')
                        ->setParameter('roles', '%"' . 'ROLE_FORMATEUR' . '"%');
                },
                'label' => 'Formateur'

            ]);
        } else { // Sinon on ressors que son nom
            $builder->add('fkAnimerUser', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('u')
                        ->where('u.email = :email')
                        ->setParameter('email', $this->security->getUser()->getUsername());
                },
                'label' => 'Formateur'
            ]);
        }
    }

    public function getChoices()
    {
        $choices = Animer::JOURNEE;
        $output = [];
        foreach ($choices as $key => $value) {
            $output[$value] = $key;
        }
        return $output;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Animer::class,
        ]);
    }

    // Evite d'avoir des trucs degueulasse dans le name des input
    public function getBlockPrefix()
    {
        return '';
    }
}
