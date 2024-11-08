<?php

namespace App\Controller\Quiz;

use App\Entity\Quiz\Quiz;
use App\Form\Type\QuizType;
use App\Repository\Quiz\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    private QuizRepository $quizRepository;

    public function __construct(QuizRepository $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    #[Route('/quizes', name: 'list_quiz')]
    public function index(): Response
    {
        $quizes = $this->quizRepository->findAllQuizes();

        return $this->render('quiz/index.html.twig', ['quizes' => $quizes]);
    }

    #[Route('/details/quiz/{id}', name: 'details_quiz')]
    public function show(int $id): Response
    {
        $quiz = $this->quizRepository->findQuizById($id);

        if (!$quiz) {
            throw $this->createNotFoundException(
                'No quiz found for id: '.$id
            );
        }

        return $this->render('quiz/details.html.twig', ['quiz' => $quiz]);
    }

    #[Route('/create/quiz', name: 'create_quiz')]
    public function create(Request $request): Response
    {
        $quiz = new Quiz;

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->quizRepository->saveQuiz($quiz);

            return $this->redirectToRoute('create_question', [
                'quizId' => $quiz->getId(),
            ]);
        }

        return $this->render('/quiz/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/quiz/{id}', name: 'update_quiz')]
    public function update(int $id, Request $request): Response
    {
        $quiz = $this->quizRepository->findQuizById($id);

        if (!$quiz) {
            throw $this->createNotFoundException('No quiz found for id: ' . $id);
        }

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->quizRepository->saveQuiz($quiz);

            return new Response('Updated quiz with name: ' . $quiz->getName() . ' and description: ' . $quiz->getDescription());
        }

        return $this->render('/quiz/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/quiz/{id}', name: 'delete_quiz')]
    public function delete(int $id): Response
    {
        $quiz = $this->quizRepository->findQuizById($id);

        if (!$quiz) {
            throw $this->createNotFoundException(
                'No quiz found for id: '.$id
            );
        }

        $this->quizRepository->deleteQuiz($quiz);

        return new Response('Succesfully deleted quiz');
    }
}
