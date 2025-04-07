<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur administrateur
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setEmail('tarckeno@gmail.com');
        $adminUser->setFirstName('Admin');
        $adminUser->setLastName('User');
        $adminUser->setSex('autre');
        $adminUser->setBirthdate(new \DateTime('1990-01-01'));
        $adminUser->setMemberType('Administrateur');
        $adminUser->setPoints(10); // Donne le niveau "expert"
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setStatus('active');

        // Hachage du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($adminUser, 'admin123');
        $adminUser->setPassword($hashedPassword);

        // Persistance en base de données
        $manager->persist($adminUser);

        // Enregistrement des changements dans la base
        $manager->flush();

        echo "Utilisateur admin créé avec succès.\n";
    }
}
