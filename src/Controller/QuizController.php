<?php

namespace App\Controller;

use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuizController extends AbstractController
{
    #[Route('/quizes', name: 'list_quiz')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $quizes = $entityManager->getRepository(Quiz::class)->findAll();

        return $this->render('index.html.twig', ['quizes' => $quizes]);
    }

    #[Route('/store/quiz', name: 'store_quiz')]
    
}
