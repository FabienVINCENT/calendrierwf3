<?php

namespace App\Controller\Admin;

use App\Entity\Formations;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 */
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
            TextField::new('nom', 'Nom de la formation'),
            AssociationField::new('localisation'),
            DateField::new('dateDebut', 'Date de début de la formation'),
            DateField::new('dateFin', 'Date de fin de la formation'),
            ColorField::new('color', 'Couleur d\'affichage'),
        ];
    }
}
