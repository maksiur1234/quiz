<?php

namespace App\Controller;

use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuestionController extends AbstractController
{
    #[Route('/quiz/question/{quizId}', name: 'app_question')]
    public function getQuestionsForQuiz(int $quizId, EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT q, qu
            FROM App\Entity\Question q
            INNER JOIN q.quiz qu
            WHERE qu.id = :quizId'
        )->setParameter('quizId', $quizId);

        $questions = $query->getResult();
        
        return $this->render('question/index.html.twig', [
            'questions' => $questions
        ]);
    }
}
