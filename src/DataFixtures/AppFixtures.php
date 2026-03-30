<?php

namespace App\DataFixtures;

use App\Entity\Questions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=0; $i<10; $i++) {
            $question = new Questions();
            $question->setId($i); 
            $question->setQuestion("Question $i");
            $question->setAnswer("Answer $i");
            $manager->persist($question);
        }
        $manager->flush();
    }
}

