<?php

namespace App\Repository\Quiz;

use App\Entity\Quiz\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function findAllQuizes(): array
    {
        return $this->findAll();
    }

    public function findQuizById(int $id): ?Quiz
    {
        return $this->find($id);
    }

    public function saveQuiz(Quiz $quiz): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($quiz);
        $entityManager->flush();
    }

    public function deleteQuiz(Quiz $quiz): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($quiz);
        $entityManager->flush();
    }
}
