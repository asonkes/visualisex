<?php

namespace App\Controller;

use App\Entity\Projects;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    // Injection de dépendance dans le constructeur
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Id utilisateur
        $user = $this->getUser();

        // Utilisateur connecté?
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Projets de l'utilisateur
        $projects = $this->entityManager
            ->getRepository(Projects::class)
            ->findBy(['user' => $user]);

        return $this->render('home/index.html.twig', [
            'projects' => $projects
        ]);
    }
}
