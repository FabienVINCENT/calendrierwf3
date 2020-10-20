<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserCrudController extends AbstractCrudController
{

    /**
     * @var string
     */
    private $passwordEncoder;

    /**
     * Class Constructor
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
       $this->passwordEncoder = $passwordEncoder;
    }

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
            TextField::new('password', 'Mot de passe')->setFormType(PasswordType::Class)->onlyOnForms(),
            TextField::new('phoneNumber', 'Numéro de téléphone'),
            ChoiceField::new('roles')->setChoices(['Admin' => 'ROLE_ADMIN', 'Formateur' => 'ROLE_FORMATEUR'])->allowMultipleChoices(),
            $talentField
        ];
    }

    // Je redéfinie la méthode persist de 'AbstractCrudController'
    public function persistEntity(EntityManagerInterface $em,$user) : void
    {
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        parent::persistEntity($em,$user);
    }

    // Je redéfinie la méthode update de 'AbstractCrudController'
    public function updateEntity(EntityManagerInterface $em,$user) : void
    {
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        parent::updateEntity($em,$user);
    }

}
