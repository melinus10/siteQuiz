<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('question', ChoiceType::class, [
            'choices' => $options['question']->getAnswers(),

            'choice_label' => function ($answer) {
                return $answer->getText();
            },

            'choice_value' => function ($answer) {
                return $answer ? $answer->getId() : '';
            },

            'expanded' => true,
            'multiple' => false,
            'label' => $options['question']->getQuestion(),
        ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
