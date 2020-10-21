<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use App\Entity\Endroit;
use App\Entity\Formations;
use App\Entity\User;
use Faker;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Provider\fr_FR\PhoneNumber;

//php bin/console doctrine:fixtures:load --append
class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Faker\Factory::create('fr_FR');

        $this->faker->addProvider(new Faker\Provider\fr_FR\PhoneNumber($this->faker));
    }

    public function load(ObjectManager $manager)
    {
        // Creation d'un admin
        $admin = new User();
        $admin->setEmail('admin@wf3.fr')
            ->setPassword($this->passwordEncoder->encodePassword(
                $admin,
                'aaaaaaaa'
            ))
            ->setLastname('Admin')
            ->setFirstname('user')
            ->setPhoneNumber('+33612345678')
            ->setRoles(['ROLE_ADMIN']);
        // On persiste l'admin
        $manager->persist($admin);

        // Création users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($this->faker->safeEmail())
                ->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        'aaaaaaaa'
                    )
                )
                ->setLastname($this->faker->lastName)
                ->setFirstname($this->faker->firstName)
                ->setPhoneNumber('+336' . $this->faker->numberBetween($min = 10000000, $max = 99999999));
            $manager->persist($user);
        }

        // Creations des compétences
        $comp = new Competence();
        $comp->setNom('Front-end');
        $manager->persist($comp);
        $comp2 = new Competence();
        $comp2->setNom('PHP');
        $manager->persist($comp2);
        $comp3 = new Competence();
        $comp3->setNom('Symfony');
        $manager->persist($comp3);
        $comp4 = new Competence();
        $comp4->setNom('Ionic');
        $manager->persist($comp4);
        $comp5 = new Competence();
        $comp5->setNom('Réseaux');
        $manager->persist($comp5);
        $comp6 = new Competence();
        $comp6->setNom('Chant');
        $manager->persist($comp6);
        $comp7 = new Competence();
        $comp7->setNom('Espagnol');
        $manager->persist($comp7);
        $comp8 = new Competence();
        $comp8->setNom('Francais');
        $manager->persist($comp8);

        // Creation des endroits
        for ($i = 0; $i < 10; $i++) {
            $endroit = new Endroit();
            $endroit->setVille($this->faker->city);
            $endroits[] = $endroit;
            $manager->persist($endroit);
        }
        // Creation des formation
        $formations = ['Developpeur web', 'TSSR', 'Espagnol', 'Apprentissage du chant'];
        $dateDebut = ['2020-10-26', '2020-02-15', '2020-03-15', '2020-04-15'];
        $dateFin = ['2020-12-15', '2020-04-15', '2020-11-15', '2020-10-22'];
        for ($i = 0; $i < 4; $i++) {
            $formation = new Formations();
            $formation->setNom($formations[$i]);
            $formation->setColor($this->faker->hexcolor);
            $formation->setDateDebut(new \DateTime($dateDebut[$i]));
            $formation->setDateFin(new \DateTime($dateFin[$i]));
            $formation->setLocalisation($endroits[array_rand($endroits)]);
            $manager->persist($formation);
        }


        // On save les données
        $manager->flush();
    }
}
