<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    #[Route('/home')]
    public function index(): Response
    {
        $text = "hello world";

        return $this->render('index.html.twig', ['text' => $text]);
    }
}