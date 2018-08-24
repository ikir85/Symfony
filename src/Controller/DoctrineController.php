<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Publication;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/doctrine")
 */

class DoctrineController extends Controller
{
    /**
     * @Route("/user/{id}")
     */

    public function index($id)
    {
        // gestionnaire d'entités de doctrine
        $em = $this->getDoctrine()->getManager();

        /**
         *
         * User::class == 'App\Entity\User'
         * Retourne un objet dont ls attributs sont settes a partir de la bdd
         * User avec l'id 1
         */

        $user = $em->find(User::class, $id);

        /*
         * En version longue
         * $repository = $em->getRepository(User::class);
         * $users = $repository ->find($id);
         */

        //s'il n'y a pas de user en bdd avec l'id passé a la nethode find() elle retourne null
        //404
        if (is_null($user)){
            throw new NotFoundHttpException();
        }

        dump($user);

        return $this->render(
            'doctrine/index.html.twig',
            [
               'user' =>$user
            ]
        );
    }

    /**
     * @Route("/create-user")
     */

     public function createUser(Request $request)
     {
         // si on a recu du POST
         if ($request -> isMethod('POST')){
            $data = $request->request->all();
            dump($data);

            $user = new User();

            $user
                ->setLastname($data['lastname'])
                ->setFirstname($data['firstname'])
                ->setEmail($data['email'])
                //le setter de birthdate attend un objet Datetime
                ->setBirthdate(new \DateTime($data['birthdate']))
                ;


         $em = $this->getDoctrine()->getManager();
         // dit au'il faudra enregistrer le User en bdd
         // au prochain apppel a la methode fluch()
         $em->persist($user);
         //enregistrement effectif
         $em->flush();
         }
         return $this->render('doctrine/create_user.html.twig');
     }

     /**
      * @Route("/list-user")
      */
     public function listUser()
     {
         $em = $this -> getDoctrine() -> getManager();
         //$repository contient une instance de App\Repository\UserRepository
         $repository = $em->getRepository(User::class);
         //retourne tous les users en bdd sous la forme d'un tableau d'ojets User
         $users = $repository->findAll();

         dump($users);

         return $this->render(
             'doctrine/list_user.html.twig',
             [
                 'users' =>$users
             ]

         );
     }

         /**
          * @Route("/search-email/{email}")
          *
          */

         public function searchEmail($email){

         $em = $this -> getDoctrine() -> getManager();

         $repository = $em->getRepository(User::class);

         //findOneby( )quand on est sûr qu'il n'ya pas plus d'un resusltat retourne
         //un objet User ou null si pas de resultat
         $user = $repository->findOneBy(['email' => $email ]);

         //404
         if (is_null($user)){
             throw new NotFoundHttpException();
         }

             return $this->render(
                 'doctrine/index.html.twig',
                 [
                     'user' =>$user
                 ]
             );
         }

    /**
     * @Route("/search-lastname/{lastname}")
     *
     */

     public function searchLastName($lastname){

        $em = $this -> getDoctrine() -> getManager();
        $repository = $em->getRepository(User::class);

        //retroune un tableau d'objets User filtrés sur le nom de famille
         // s'il n'ya qucun resultat, retroune un tabelau vide
        $users = $repository->findBy([
            'lastname' => $lastname,
            //'firstname' => $firstname,
            //si on voulais chercher sur plusieur critères

        ]);

        return $this->render(
            'doctrine/list_user.html.twig',
            [
                'users' =>$users
            ]
        );
    }

    /**
     * Le parametre dans l'url s'apppelle id comme la clé primaire de la table user
     * En typant user le parametre passé à la methode
     * on recupere dans $author un objet user qui a cet id
     *
     * @Route("/publication/author/{id}")
     *
     */

    public function publicationByAuthor( User $author){
        dump($author);

        $em = $this -> getDoctrine() -> getManager();
        $repository = $em->getRepository(Publication::class);

        $publications = $repository->findBy([
            'author' => $author,
        ]);

        dump($publications);

        return $this->render(
            'doctrine/publications.html.twig',
            [
                'publications' => $publications
            ]
        );
    }

    /**
     *
     * @Route("/author/{id}/publications")
     *
     */
    public function userPublications(user $user)
    {
        /**
         * en appelant le getter de l'attribut publications
         * d'un objet User, Doctrine va automatiquement faire
         * une requete en bdd pour y mettre les publications liées
         * à ce user grace a l'annocation @OR?\OneToMany sur l'attribut
         */
        return $this->render(
            'doctrine/user_publications.html.twig',
            [
                'user' => $user
            ]
        );

    }

    /**
     * @param Request $request
     * @Route("/create-user-with-publication")
     */
    public function createUserWithPublications(Request $request)
    {
        if ($request -> isMethod('POST')){
            $data = $request->request->all();

            $user = new User();

            $user
                ->setLastname($data['lastname'])
                ->setFirstname($data['firstname'])
                ->setEmail($data['email'])
                //le setter de birthdate attend un objet Datetime
                ->setBirthdate(new \DateTime($data['birthdate']))
            ;

            $publication = new Publication();
            $publication
                ->setTitle($data['title'])
                ->setContent($data['content'])
                ->setAuthor($user)
            ;

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->persist($publication);
            $em ->flush();

        }

        return $this->render('doctrine/create_user_with_publication.html.twig');
    }

    /**
     * @param Request $request
     * @Route("/create-user-with-publication2")
     */
    public function createUserWithPublications2(Request $request)
    {
        if ($request -> isMethod('POST')){
            $data = $request->request->all();

            $user = new User();

            $user
                ->setLastname($data['lastname'])
                ->setFirstname($data['firstname'])
                ->setEmail($data['email'])
                ->setBirthdate(new \DateTime($data['birthdate']))
            ;

            $publication = new Publication();
            $publication
                ->setTitle($data['title'])
                ->setContent($data['content'])
                //->setAuthor($user)
            ;

            $user->addPublication($publication);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            //$em->persist($publication);

            /**
             * grace a cascade={"persist} ajouté dans l'annotation
             * OneToMany sur l'attribut $publications de User
             * plus besoin d'appeler persist() sur la publication
             * pour qu'elle soit enregistrée eb bdd
             */
            $em ->flush();

        }

        return $this->render('doctrine/create_user_with_publication.html.twig');
    }

    /**
     *
     * @Route("/users/group/{id}")
     */
    public function usersByGroup(Group $group)
    {
        return $this->render(
            'doctrine/user_by_group.html.twig',
            [
                'group' => $group
            ]
        );


    }

    /**
     * @Route ("/add-user-to-group/{id}")
     *
     *
     */
    public function AddUserToGroup(Request $request, Group $group)
    {
        //entity manager : permet de manipuler les entités de Doctrine
        //et leur relation à la bdd
        $em = $this -> getDoctrine() -> getManager();
        // renvoie UserRepository
        $repository = $em->getRepository(User::class);
        //renvoie tous les users de la bdd sous la forme d'un tableau d'objets User
        $users = $repository->findAll();

        //dump($users);

        if ($request -> isMethod('POST')){
            //£_POST['user']
            $userId= $request->request->get('user');
            //l'objet user aaui a l'id que l'on a recu en POST
            $user = $repository->find($userId);
            // ajout du User à la collection d'objets User du Group
            $group->getUsers()->add($user);
            //enregistrement du Group en bdd
            //qui va enregistrer l'id du Group et celui du User dans la table
            //de relation user_grou
            $em->persist($group);
            $em->flush();
        }

        return $this->render(
            'doctrine/add_user_to_group.html.twig',
            [
                'users' =>$users,
                // et l'objet group
                'group' =>$group
            ]
        );

    }
}


