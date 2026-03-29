<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    #[Route(path:'/quiz' , name : 'quiz')]
    public function quiz(Request $request) {
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

    }

}

