<?php

namespace App\DataFixtures;

use App\Entity\Answers;
use App\Entity\Questions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=0; $i<10; $i++) {
            $question = new Questions();
            $question->setQuestion("Question $i");
            for($j=0; $j<4; $j++) {
                $answer = new Answers();
                $answer->setText("Answer $j for question $i");
                $answer->setIsCorrect($j === 0);
                $answer->setQuestion($question);
                $manager->persist($answer);
            }
            $manager->persist($question);
        }
        $manager->flush();
    }
}

