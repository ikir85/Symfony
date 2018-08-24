<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/http")
 */
class HttpController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('http/index.html.twig', [
            'controller_name' => 'HttpController',
        ]);
    }

    /**
     * @Route("/request")
     */
    public function request(Request $request)
    {
        // -- la fonction dump() vient du framework, elle fait un var_dump dont le résultat se retrouve dans la barre de debug
        var_dump($_GET);
        // -- $request->query est l'attribut de l'objet Request qui fait référence à $_GET
        // -- sa méthode all() retourne la totalité du tableau $_GET
        dump($request->query->all());

        // -- var_dump($_GET['nom'];
        // -- renvoie null si le paramètre n'existe pas dans la query string
        dump($request->query->get('nom'));

        // -- renvoie 'anonyme' si le paramètre n'existe pas dans la query string --> valeur par défaut
        dump($request->query->get('nom', 'anonyme'));

        // -- GET ou POST
        dump($request->getMethod());

        if ($request->isMethod('POST')) {
            echo 'On a reçu des données de formulaire en POST';

            // -- $request->query est l'attribut de l'objet Request qui fait référence à $_POST
            dump($request->request->all());

            // -- la méthode get() de $request->request fonction de la même manière que celle de $request->query
            dump($request->request->get('nom'));
        }

        if (!$request->isXmlHttpRequest()) {
            echo "<p>La page n'est pas appelée en AJAX</p>" ;
        }

        return $this->render(
            'http/request.html.twig'
        );
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/session")
     */
    public function session(Request $request)
    {
        // -- pour accéder à la session
        $session = $request->getSession();

        // -- ajoute un élément 'nom' valant 'ANEST' à la session
        // -- $_SESSION['nom'] = 'ANEST';
        $session->set('nom', 'ANEST');
        $session->set('prenom', 'Julien');

        // -- accède à l'élément 'nom' de la session
        // var_dump($_SESSION('nom'));
        dump($session->get('nom'));

        // var_dump($_SESSION);
        dump($session->all());

        // -- supprime un élément de la session
        $session->remove('nom');

        // -- nettoie la totalité de la session
        $session->clear();

        return $this->render(
            'http/session.html.twig'
        );
    }

    /**
     * @param Request $request
     * @Route("/response")
     */
    public function response(Request $request)
    {
        //-- une méthode de contrôleur doit forcément retourner un objet Response
        // -- une réponse qui contient du texte brut
        $response = new Response('Ma réponse');

        // -- if (isset($_GET['type']) && $_GET['type'] == 'twig) {
        if ($request->query->get('type') == 'twig'){
            // -- $this->render('...') retourne un objet Response dont le contenu est le html construit par le template
            $response = $this->render('http/response.html.twig');
        } elseif ($request->query->get('type') == 'json') {
            $exemple = [
                'nom' => 'ANEST',
                'prenom' => 'Julien'
            ];

            // -- transforme le tableau $exemple en JSON
            // -- passe Content-Type: application/json dans les entêtes HTTP
            // -- et envoie le json dans le contenu de l'objet Response
            $response = new JsonResponse($exemple);
        }

        if ($request->query->get('found') == 'no') {
            // -- pour retourner une 404
            throw new NotFoundHttpException();
        }

        if ($request->query->get('redirect') == 'index') {
            // -- redirige vers la page dont la route a pour nom app_http_index
            $response = $this->redirectToRoute('app_http_index');
        }

        if ($request->query->get('redirect') == 'bonjour') {
            // -- redirige vers une route dont l'url a une partie variable en lui passant une valeur pour cette
            // partie variable {qui}
            $response = $this->redirectToRoute(
                'app_index_bonjour',
                [
                    'qui' => 'Julien'
                ]
            );
        }

        return $response;
    }

    /**
     * @Route("/flash")
     */
    public function flash()
    {   // -- ajoute un message flash de type success
        $this->addFlash('success', 'Message de succès');

        return $this->redirectToRoute('app_http_flashed');
    }

    /**
     * @Route("/flashed")
     */
    public function flashed()
    {
        return $this->render('http/flashed.html.twig');
    }
}

