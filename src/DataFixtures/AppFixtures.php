<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Projects;
use App\Entity\Tasks;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordEncoder) {}

    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setEmail('audrey.sonkes@gmail.com');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'admin')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // Créer des utilisateurs aléatoires
        $faker = Factory::create('fr-FR');
        $users = [];

        for ($i = 1; $i <= 10; $i++) {
            $user = new Users();
            $user->setEmail($faker->email);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, '123456!')
            );
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $users[] = $user;
        }

        // Créer des projets et les associer à des utilisateurs distincts
        foreach ($users as $user) {
            // Créer 1 projet par utilisateur
            for ($i = 1; $i <= 1; $i++) {
                $project = new Projects();
                $project->setName($faker->text(10));

                // Associe chaque projet à l'utilisateur actuel de la boucle
                $project->setUser($user);

                // Persist le projet
                $manager->persist($project);

                // Associe la tâche à ce projet plus tard
                // Créer 8 tâches par projet
                for ($j = 0; $j < 8; $j++) {
                    $task = new Tasks();
                    $task->setName($faker->text(10));

                    // Associe chaque tâche à l'utilisateur actuel
                    $task->setUser($user);

                    // Ajoute la tâche au projet
                    $project->addTask($task);  // Cela va aussi mettre à jour l'autre côté de la relation

                    // Persist la tâche
                    $manager->persist($task);
                }
            }
        }

        $manager->flush();
    }
}
