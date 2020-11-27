<?php 

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Recette;
use App\Entity\User;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



/**
 * @Route ("/admin/recette" , name="admin.")
 */


class AdminRecetteController extends AbstractController
{
    private  RecetteRepository $repository;
    private EntityManagerInterface $em;
    private AuthenticationUtils $authenticationUtils;
    
    public function __construct(RecetteRepository $repository, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->authenticationUtils = $authenticationUtils;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route ("/" , name="recette.index")
     */
    public function index() : Response
    {

      
      $recettes = $this->repository->findAll();
      return $this->render("admin/recette/index.html.twig", [
        'current_menu_recettes' => "recette",
        "recettes" => $recettes
        
      ]);
        
      
    }



  /**
   * @Route("/created" , name="recette.create")
   */

  public function created( Request $request)
  {


    $error = $this->authenticationUtils->getLastAuthenticationError();
    $recette = new Recette();

    

    $form = $this->createForm(RecetteType::class, $recette);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $message = "Création réussis ! ";
      $recette->setCreatedAt(new DateTime());
      $recette->setAutheur($this->getUser());
      $this->em->persist($recette);
      $this->em->flush();
      $this->addFlash('success', $message);
      return $this->redirectToRoute("admin.recette.index", [], 301);
    }
    return $this->render("admin/recette/create.html.twig", [
      'recettes' => $recette,
      'current_menu_recettes' => "recette",
      'error' => $error,

      'form' => $form->createView()
    ]);
  }



  /**
   * @Route ("/{id}" , name="recette.edit", methods="GET|POST")
   */

    public function edit(Recette $recette, Request $request): Response
    {
    $error = $this->authenticationUtils->getLastAuthenticationError();
      $form = $this->createForm(RecetteType::class, $recette);

      // Regarde la requete et l'ensemble des champs grace au setter de la propriéter Recette
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid())
      {
        $message = "Modification réussis ! ";
          $this->em->flush();
          $this->addFlash('success', $message);
         return $this->redirectToRoute("admin.recette.index", [], 301);
      }

      return $this->render("admin/recette/edit.html.twig", [
        'recette' => $recette,
      'current_menu_recettes' => "recette",
        'error' => $error,
        'form' => $form->createView()
      ]);
    }


    /**
     * @Route ("/{id}" , name="recette.delete" , methods="DELETE")
     */

    public function delete(Recette $recette, Request $request)
    {
      
      if($this->isCsrfTokenValid('token_id',$request->get('_token') ))
      {
        $message = "Suppression réussis ! ";
        $this->em->remove($recette);
        $this->em->flush();
        $this->addFlash('success', $message);
      }
      
      return $this->redirectToRoute("admin.recette.index", [], 301);

    }


 
}
?>