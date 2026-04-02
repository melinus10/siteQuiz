<?php

namespace App\Form;
use App\Entity\Questions;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class QuizType extends AbstractType
{
    private array $questions;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->questions = $doctrine->getRepository(Questions::class)->findAll();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->questions as $question) {

            $builder->add('question_' . $question->getId(), ChoiceType::class, [
                'choices' => $question->getAnswers(),
                
                'choice_label' => function($answer) {
                    return $answer->getText();
                },

                'choice_value' => function($answer) {
                    return $answer ? $answer->getId() : '';
                },

                'expanded' => true,   
                'multiple' => false, 
                'label' => $question->getQuestion(),
            ]);
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class' => null,
        ]);
    }
}
