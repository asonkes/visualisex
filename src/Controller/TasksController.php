<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Entity\Projects;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TasksController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/tasks', name: 'app_tasks')]
    public function index(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer les projets de l'utilisateur
        $projects = $this->entityManager
            ->getRepository(Projects::class)
            ->findBy(['user' => $user]);

        // Tableau pour stocker les tâches classées par projet
        $tasksTable = [];

        // Pour chaque projet de l'utilisateur, récupérer les tâches
        foreach ($projects as $project) {
            // On récupère toutes les tâches qui sont liées à ce projet
            $tasks = $this->entityManager
                ->getRepository(Tasks::class)
                ->createQueryBuilder('t')
                ->join('t.project', 'p') // On fait la jointure avec la relation ManyToMany
                ->where('p.id = :projectId') // On filtre par le projet
                ->setParameter('projectId', $project->getId())
                ->getQuery()
                ->getResult();

            // On ajoute les tâches dans le tableau avec l'ID du projet comme clé
            $tasksTable[$project->getId()] = $tasks;
        }

        // Rendre la vue avec les projets et les tâches associées
        return $this->render('tasks/index.html.twig', [
            'projects' => $projects,
            'tasksTable' => $tasksTable,
            'user' => $user
        ]);
    }
}
