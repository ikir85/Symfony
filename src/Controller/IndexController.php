<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * Annotation de routing :
     * définit l'url de la page qui exécute le contenu
     * de la méthode qui suit
     * @Route("/hello")
     */

     public function hello()
     {
         // le chemin du template qui fait l'affichage
         // a partir de la racine du repertoire template
        return $this->render('index/hello.html.twig');
     }

    /**
     *
     * @Route("/bonjour/{qui}")
     */
     public function bonjour($qui = 'toi')
     {
         return $this->render('index/bonjour.html.twig',
             [
                 'qui' =>$qui
             ]);
     }

    /**
     * Le parametre devient optionel en lui donnant une valeur par defaut grace au paramètre par defaults
     * que l'on passe a la route au format json
     * La route matche /salut/unNom et /salut
     *
     * @Route("/salut/{nom}", defaults = {"nom": "toi"})
     */
    public function salut($nom)
    {
        return $this->render('index/salut.html.twig',
            [
                'nom' =>$nom
            ]);
    }

    /**
     * Une route avec 2 parties variables dont une optionnelle
     * matche /coucou/Julien et coucou/Julien-Anest
     *
     * @Route("/coucou/{prenom}-{nom}", defaults = {"nom": ""})
     */
    public function coucou($prenom, $nom)
    {
       $nomComplet = rtrim( $prenom . ' '. $nom);

        return $this->render('index/coucou.html.twig',
            [
                'nom' =>$nomComplet
            ]);
    }

    /**
     *
     * @Route("/modifier-categorie/{id}", requirements={"id"="\d+"})
     *
     */
    public function modifierCategorie($id)
    {
        preg_match ('/\d+/');
        return $this->render('index/modifier_categorie.html.twig',
            [
                'id' =>$id
            ]);
    }
}
