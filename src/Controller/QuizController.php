<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\Type\QuizType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuizController extends AbstractController
{
    #[Route('/quizes', name: 'list_quiz')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $quizes = $entityManager->getRepository(Quiz::class)->findAll();

        return $this->render('quiz/index.html.twig', ['quizes' => $quizes]);
    }

    #[Route('/details/quiz/{id}', name: 'details_quiz')]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);

        if (!$quiz) {
            throw $this->createNotFoundException(
                'No quiz found for id: '.$id
            );
        }

        return $this->render('quiz/details.html.twig', ['quiz' => $quiz]);
    }

    #[Route('/create/quiz', name: 'create_quiz')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $quiz = new Quiz;

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('create_question', [
                'quizId' => $quiz->getId(),
            ]);
        }

        return $this->render('/quiz/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/quiz/{id}', name: 'update_quiz')]
    public function update(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);
        $quiz->getName();
        $quiz->getDescription();

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($quiz);
            $entityManager->flush();

            return new Response('Updated quiz with name: ' . $quiz->getName() . ' and description: ' . $quiz->getDescription());
        }

        return $this->render('/quiz/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/quiz/{id}', name: 'delete_quiz')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $quiz = $entityManager->getRepository(Quiz::class)->find($id);

        if (!$quiz) {
            throw $this->createNotFoundException(
                'No quiz found for id: '.$id
            );
        }

        $entityManager->remove($quiz);
        $entityManager->flush();

        return new Response('Succesfully deleted quiz');
    }
}
