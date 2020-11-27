<?php 

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



/**
 * @Route ("/admin" , name="admin.")
 */


class AdminCategorieController extends AbstractController
{
    private  CategoryRepository $repository;
    private EntityManagerInterface $em;
  private AuthenticationUtils $authenticationUtils;
    public function __construct(CategoryRepository $repository, EntityManagerInterface $em, AuthenticationUtils $authenticationUtils)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->authenticationUtils = $authenticationUtils;
    }

  /**
   * @Route ("/categorie" , name="categorie.index")
   */
    public function index() : Response
    {
      $categories = $this->repository->findAll();
      return $this->render("admin/categorie/index.html.twig", [
      "current_menu_categories" => "categorie" ,
      "categories" => $categories]);
    }



  /**
   * @Route("categorie/created" , name="categorie.create")
   */

  public function created( Request $request)
  {
    $error = $this->authenticationUtils->getLastAuthenticationError();
    $categorie = new Category();
    $form = $this->createForm(CategoryType::class, $categorie);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $message = "Création réussis ! ";
      $this->em->persist($categorie);
      $this->em->flush();
      $this->addFlash('notice', $message);
      return $this->redirectToRoute("admin.categorie.index" , [], 301);
    }
    return $this->render("admin/categorie/create.html.twig", [
      'categorie' => $categorie,
      'error' => $error,
      'form' => $form->createView()
    ]);
  }



  /**
   * @Route ("/categorie/{id}" , name="categorie.edit", methods="GET|POST")
   */

    public function edit(Category $categorie, Request $request): Response
    {
      $form = $this->createForm(CategoryType::class, $categorie);

      // Regarde la requete et l'ensemble des champs grace au setter de la propriéter Recette
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid())
      {
        $message = "Modification réussis ! ";
          $this->em->flush();
          $this->addFlash('success', $message);
         return $this->redirectToRoute("admin.categorie.index", [], 301);
      }

      return $this->render("admin/categorie/edit.html.twig", [
      'categories' => $categorie,
        'form' => $form->createView()
      ]);
    }


  /**
   * @Route ("/categorie/{id}" , name="categorie.delete" , methods="DELETE")
   */

    public function delete(Category $categorie, Request $request)
    {
      if($this->isCsrfTokenValid('token_id',$request->get('_token') ))
      {
        $message = "Suppression réussis ! ";
        $this->em->remove($categorie);
        $this->em->flush();
      $this->addFlash('success', $message);
      }
      
      return $this->redirectToRoute("admin.categorie.index", [], 301);

    }


 
}
?> 