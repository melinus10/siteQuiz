<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class MainPageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function affichagePageAccueil()
    {
        return $this->render('main.html.twig');
    }
}

