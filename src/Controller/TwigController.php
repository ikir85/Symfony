<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Préfixe de route accolé à chaque url définie dans les routes des méthodes du controller
 *
 * @Route("/twig")
 */
class TwigController extends Controller
{
    /**
     * Ici, l'url de la route est /twig/ parce qu'il y a le préfixe de route défini au dessus de la classe
     *
     * @Route("/")
     */
    public function index()
    {
        return $this->render(
            'twig/index.html.twig',
            [
                'auj' => new \DateTime()
            ]
        );
    }
}

