<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{ 
    /**
     * @Route("/home", name="homepage")
     * 
     * @return void
     */
    public function home(Article $article){
        return $this->render('home.html.twig', [
            'article' => $article
        ]);  
    }

}
?>