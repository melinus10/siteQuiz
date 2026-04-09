<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Form\QuestionsType;
use App\Repository\QuestionsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function list(QuestionsRepository $repo): Response
    {
        $questions = $repo->findAll();

        return $this->render('list.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/admin/question/new', name: 'admin_question_new')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Request $request, ManagerRegistry $em): Response
    {
        $question = new Questions();

        for ($i = 0; $i < 4; $i++) {
            $answer = new Answers();
            $question->addAnswer($answer);
        }

        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->getManager()->persist($question);
            $em->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/question/edit/{id}', name: 'admin_question_edit')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(Questions $question, Request $request, ManagerRegistry $em): Response
    {
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->getManager()->flush();

            return $this->redirectToRoute('admin_question_list');
        }

        return $this->render('edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/question/delete/{id}', name: 'admin_question_delete')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(Questions $question, ManagerRegistry $em): Response
    {
        $em->getManager()->remove($question);
        $em->getManager()->flush();

        return $this->redirectToRoute('admin_question_list');
    }
}
