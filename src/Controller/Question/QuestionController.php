<?php

namespace App\Controller\Question;

use App\Entity\Question\Question;
use App\Entity\Quiz\Quiz;
use App\Form\Type\QuestionType;
use App\Repository\Question\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/question', name: 'question_')]
#[IsGranted('ROLE_USER')]
class QuestionController extends AbstractController
{
    private $questionRepository;
    private $entityManager;
    public function __construct(QuestionRepository $questionRepository, EntityManagerInterface $entityManager)
    {
        $this->questionRepository = $questionRepository;
        $this->entityManager = $entityManager;
    }
    #[Route('/{quizId}', name: 'app_question')]
    public function getQuestionsForQuiz(int $quizId): Response
    {
       $questions = $this->questionRepository->getQuestionForQuiz($quizId, $this->entityManager);
        
        return $this->render('question/index.html.twig', [
            'questions' => $questions
        ]);
    }

    #[Route('/create/{quizId}', name: 'create_question')]
    public function create(EntityManagerInterface $entityManager, Request $request, int $quizId): Response
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($quizId);

        if (!$quiz) {
            throw $this->createNotFoundException('Quiz not found');
        }

        $question = new Question;

        $questionForm = $this->createForm(QuestionType::class, $question);
        $questionForm->handleRequest($request);

        if ($questionForm->isSubmitted() && $questionForm->isValid()) {

            $question->setQuiz($quiz);

            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('quiz_quiz_list');
        }

        return $this->render('/question/create.html.twig', [
            'form' => $questionForm
        ]);
    }
}
