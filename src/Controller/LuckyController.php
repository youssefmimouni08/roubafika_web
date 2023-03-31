<?php
// src/Controller/LuckyController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class LuckyController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function homepage(): Response
    {
        

        return $this->render('roubafika/home.html.twig');
    }
    public function baha(): Response
    {
        return new Response(
            '<html><body> baha miboun </body></html>'
        );
    }
}