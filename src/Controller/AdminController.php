<?php 
namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Form\QuestionsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/question/new', name: 'admin_question_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $question = new Questions();

        for ($i = 0; $i < 4; $i++) {
            $answer = new Answers();
            $question->addAnswer($answer); 
        }

        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($question);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('addQuestion.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
