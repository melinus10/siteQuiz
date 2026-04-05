<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuizType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuizController extends AbstractController
{
    #[Route(path: '/quiz', name: 'quiz')]
    public function quiz(Request $request): Response
    {
        $form = $this->createForm(QuizType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $score = 0; 
            foreach ($data as $reponseChoisie) {
                if ($reponseChoisie->isCorrect()) { 
                    $score++; 
                }
            }
        }
        return $this->render('quiz.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('quiz/{id}' , name: 'quiz_qst')]
    public function quiz_qst(Questions $question)
    {
       $form = $this->createForm(QuizType::class, null, [
        'question' => $question
    ]);
        return $this->render('quiz.html.twig' , [
            'question' => $question,
            'form' => $form->createView() 
            ]) ;
    }
}
