<?php

namespace App\DataFixtures;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
        public function __construct(UserPasswordHasherInterface $passwordHasher)
        {
            $this->passwordHasher = $passwordHasher;
        }   

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

        $faker = Factory::create() ;
        for($i=0; $i<10; $i++) {
        $user = new User($this->passwordHasher);
        $user->setUsername($faker->userName());
        $user->setPassword("1234567");
        $manager->persist($user);    
            
        }
        $manager->flush();
    }
}

