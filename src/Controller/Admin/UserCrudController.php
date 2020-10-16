<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname', 'Nom'),
            TextField::new('lastname', 'Prénom'),
            TextField::new('email', 'Email'),
            TextField::new('phoneNumber', 'Numéro de téléphone'),
            ChoiceField::new('roles')->setChoices(['Admin'=>'ROLE_ADMIN','Formateur'=>'ROLE_FORMATEUR'])->allowMultipleChoices(),
        ];
    }

}
