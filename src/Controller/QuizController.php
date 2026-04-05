<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuizType;
use App\Repository\QuestionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuizController extends AbstractController
{
    #[Route('quiz/{id}', name: 'quiz')]
    public function quiz(Request $request, Questions $question , QuestionsRepository $repo ): Response
    {
        $form = $this->createForm(QuizType::class, null, [
            'question' => $question
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $score = 0;
            foreach ($data as $reponseChoisie) {
                if ($reponseChoisie->isCorrect()) {
                    $score++;
                }
            }
            $nextQuestion = $repo->findNextQuestion($question->getId());
            if ($nextQuestion) {
                return $this->redirectToRoute('quiz', ['id' => $nextQuestion->getId()]);
            } else {
                return $this->render('results.html.twig', [
                    'score' => $score
                ]);
            }
        }
        return $this->render('quiz.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
