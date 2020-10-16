<?php

namespace App\Controller\Admin;

use App\Entity\Formations;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class FormationsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formations::class;
    }

    // la configuration du formulaire  pour créer une formation
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            AssociationField::new('localisation'),
        ];
    }

}
