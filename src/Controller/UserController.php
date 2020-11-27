<?php
// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Recette;

use App\Form\ProfilType;


use App\Form\FormUserType;
use App\Form\FormUsersType;
use App\Repository\CommentaireRepository;
use App\Repository\RecetteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private EntityManagerInterface $em;
    private AuthenticationUtils $authenticationUtils;
    
    public function __construct(EntityManagerInterface $em , AuthenticationUtils $authenticationUtils)
    {
        $this->em = $em;
        $this->authenticationUtils = $authenticationUtils;
        
    }

    /**
     * @Route ("/profile/{id}" , name="profile.edit", methods="GET|POST")
     */

    public function edit(User $user, Request $request): Response
    {
        
        if ($user->getId() !== $this->getUser()->getId()) {

            return $this->redirectToRoute("home");
        }
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $form = $this->createForm(ProfilType::class, $user);

        // Regarde la requete et l'ensemble des champs grace au setter de la propriéter Recette
        $form->handleRequest($request);

        
        if ($form->isSubmitted()) {
            $message = "Modification réussis ! ";
            $this->em->flush();
            $this->addFlash('success', $message);
            return $this->redirectToRoute("profile", [
                'id' => $user->getId()
            ], 301);
           
        }
                return $this->render("profil/edit.html.twig", [
                'user' => $user,
                'current_menu_editUser' => "editUser",
                'error' => $error,
                'forme' => $form->createView()
            ]);
        
    }

 
    /**
     * @Route("/profile{id}", name="profile"  )
     * @return Response
     */
    public function profilUser(User $user, UserRepository $userRepository, CommentaireRepository $commentaireRepository, RecetteRepository $recetteRepository)
    {

        $users = $userRepository->findBy(['id' => $user->getId()]);
        $comm = $commentaireRepository->findAllComm($user->getId());
        $recette =  $recetteRepository->findAllRecette($user->getId());
        $recettes =  $recetteRepository->findAllRecette($user->getId());

        
      
      
        return $this->render("profil/index.html.twig", [
            "current_menu_profil" => "profil",
            "users" => $users,
            "comm" => count($comm),
            "recette" => count($recette),
            "recettes" => $recettes

        ]);
    }





    
    /**
     * @Route("/signUp", name="sign.up")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        // 1) build the form
        $user = new User();
        
        // $user->getAutheur();
        $forms_user = $this->createForm(FormUsersType::class, $user);
        
        // 2) handle the submit (will only happen on POST)
        $forms_user->handleRequest($request);

        if ($forms_user->isSubmitted() && $forms_user->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            
            // 4) save the User!
           
            $this->em->persist($user);
            $message = "Compte crée ! Connectez-vous ";
            $this->em->flush();
            $this->addFlash('success', $message);
            
            return $this->redirectToRoute('login', [], 301);
           
        }

        return $this->render(
            'pages/signUp.html.twig',
            ['forms_user' => $forms_user->createView(),
            "form_create" => "createUser",
                'error' => $error ]
        );
    }
}
