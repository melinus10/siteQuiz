<?php

namespace App\Controller;

use App\Form\QuizType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuizController extends AbstractController
{
    #[Route(path: '/quiz', name: 'quiz')]
    /* public function quiz(Request $request) {
        $result = null ; 
        if($request->isMethod('POST')) {
            $respons = $request->request->get('answer');
        
        if($respons === "Paris")
            {
                $result="Correct!";
            } else {
                $result="Incorrect!";
            } 
        }
        return $this->render('quiz.html.twig', ['result' => $result]);

    }*/

    public function quiz(): Response
    {
        $form = $this->createForm(QuizType::class);

        return $this->render('quiz.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}