<?php

namespace App\Controller\Admin;

use App\Entity\Endroit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class EndroitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Endroit::class;
    }

    // fonction pour changer le titre de la page
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle('index', 'Gestion des endroits');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('ville', 'Lieux de formation'),
        ];
    }

}
