<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $question = $options['question'];

        $builder->add('reponse', ChoiceType::class, [
            'choices' => $question->getAnswers(),
            'choice_label' => 'text',
            'expanded' => true,
            'multiple' => false,
            'label' => $question->getQuestion(),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'question' => null, 
        ]);
    }
}
