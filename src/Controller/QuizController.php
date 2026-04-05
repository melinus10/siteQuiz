<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\QuizType;
use App\Repository\AnswersRepository;
use App\Repository\QuestionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuizController extends AbstractController
{
    #[Route('quiz/{id}', name: 'quiz')]
    public function quiz(Request $request, Questions $question, QuestionsRepository $questionsRepo , AnswersRepository $AnswersRepo): Response
    {
        $form = $this->createForm(QuizType::class, null, [
            'question' => $question
        ]);
        $form->handleRequest($request);
        $sessionAnswers = $request->getSession()->get('userReponses', []);
        if ($form->isSubmitted() && $form->isValid()) {
            $userAnswerArray = $form->getData();
            $sessionAnswers[$question->getId()] = $userAnswerArray['reponse']->getId();
            $request->getSession()->set('userReponses', $sessionAnswers);
            $nextQuestion = $questionsRepo->findNextQuestion($question->getId());
            if ($nextQuestion) {
                return $this->redirectToRoute('quiz', ['id' => $nextQuestion->getId()]);
            } else {
                $sessionAnswers = $request->getSession()->get('userReponses', []);
                $results = [];
                foreach ($sessionAnswers as $qId => $rId) {
                    $answerObj = $AnswersRepo->find($rId);
                    $results[] = $answerObj;
                }
                $score = 0;
                foreach ($results as $res) {
                    if ($res->isCorrect()) $score++;
                }
                $request->getSession()->remove('userReponses'); 
                return $this->render('results.html.twig', [
                    'results' => $results,
                    'score' => $score,
                    'maxScore' => count($results)
                ]);
            }
        }

        return $this->render('quiz.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
