<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('home/index.html.twig', [
            'user'=>$user
        ]);
    }
    #[Route('/test', name: 'app_candid')]
    public function index1(): Response
    {
        $user = $this->getUser();
        return $this->render('candidate/index.html.twig', [
            'user'=>$user
        ]);
    }
}
