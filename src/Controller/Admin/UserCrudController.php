<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // la méthode pour personnaliser les champs à afficher
    public function configureFields(string $pageName): iterable
    {
        // Permet de passe de l'affichage array sur la list (permet de voir les talents et pas le nombre )
        // A une associationField pour permettre d'associer les competences au formateur
        if (Crud::PAGE_INDEX === $pageName) {
            $talentField = ArrayField::new('talents', 'Compétences');
        } else {
            $talentField = AssociationField::new('talents', 'Compétences');
        }


        return [
            TextField::new('firstname', 'Nom'),
            TextField::new('lastname', 'Prénom'),
            TextField::new('email', 'Email'),
            TextField::new('phoneNumber', 'Numéro de téléphone'),
            ChoiceField::new('roles')->setChoices(['Admin' => 'ROLE_ADMIN', 'Formateur' => 'ROLE_FORMATEUR'])->allowMultipleChoices(),
            $talentField
        ];
    }
}
