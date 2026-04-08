<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Form\QuestionsType;
use App\Repository\QuestionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_question_list')]
    public function list(QuestionsRepository $repo): Response
    {
        $questions = $repo->findAll();

        return $this->render('list.html.twig', [
            'questions' => $questions,
        ]);
    }

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

        return $this->render('new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/question/edit/{id}', name: 'admin_question_edit')]
    public function edit(Questions $question, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin_question_list');
        }

        return $this->render('edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/question/delete/{id}', name: 'admin_question_delete')]
    public function delete(Questions $question, EntityManagerInterface $em): Response
    {
        $em->remove($question);
        $em->flush();

        return $this->redirectToRoute('admin_question_list');
    }
}
