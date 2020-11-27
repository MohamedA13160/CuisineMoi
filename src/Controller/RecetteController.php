<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator as OrmPaginator;
use App\Entity\Category;
use DateTime;

use App\Entity\Recette;
use Symfony\Flex\Recipe;
use App\Form\RecetteType;
use App\Entity\Ingredient;
use Cocur\Slugify\Slugify;
use App\Entity\Commentaire;
use App\Entity\RecetteSearch;
use App\Form\IngredientType;
use App\Form\CommentaireType;
use App\Form\RecetteSearchType;
use App\Form\RecipeIngredientType;
use App\Repository\CategoryRepository;
use App\Repository\RecetteRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as PaginationPaginator;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RecetteController extends AbstractController
{
    private RecetteRepository $repository;
    private AuthenticationUtils $authenticationUtils;
    private EntityManagerInterface $em;
    
    
    public function __construct(RecetteRepository $repository, AuthenticationUtils $authenticationUtils, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->authenticationUtils = $authenticationUtils;
        
        
    }

    /**
     * @Route ("/recette" , name="recette.index")
     * 
     */
    public function index(PaginatorInterface $paginator, Request $request) : Response
    {
        
       

        $search = new RecetteSearch();
        $form = $this->createForm(RecetteSearchType::class, $search);
        $form->handleRequest($request);

        $entrer = $paginator->paginate($this->repository->findAllEntrer(),$request->query->getInt('page', 1),9);
        $plat = $paginator->paginate($this->repository->findAllPlat(),$request->query->getInt('page', 1),9);
        $dessert = $paginator->paginate($this->repository->findAllDessert(),$request->query->getInt('page', 1),9);
        $boisson = $paginator->paginate($this->repository->findAllBoisson(),$request->query->getInt('page', 1),9);

        $recettes = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 9);
        
        return $this->render("recette/index.html.twig", [
        "recettes" => $recettes,
        "entrer" => $entrer,
        "plat" => $plat,
        "dessert" => $dessert,
        "boisson" => $boisson,
        "current_menu_recettes" => "recettes",
        'form' => $form->createView()
         
      ]);
    }

    /**
     * @Route("/created" , name="recette.create")
     */
    public function created(Request $request)
    {
        
       
        $recette = new Recette();
      
        
        $form = $this->createForm(RecetteType::class, $recette);
        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = "Création réussis ! ";
            $recette->setCreatedAt(new DateTime());
            $recette->setAutheur($this->getUser());
            
            
            
            $this->em->persist($recette);
            $this->em->flush();
            $this->addFlash('notice', $message);
            return $this->redirectToRoute("recette.index", [], 301);
        }
        return $this->render("recette/create.html.twig", [
            'recette' => $recette,
            'current_menu_recettes' => "recette",
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/created" , name="recette.create")
     */
    
    /**
     * @Route("/recette/{slug}-{id}", name="recette.show" , requirements={ "slug" = "[a-z0-9\-]*" } )
     * @return Response
     */
    public function show(Recette $recette, string $slug, CommentaireRepository $commentaireRepository, Request $request): Response
    {

       $comments = $commentaireRepository->findBy(['recette' => $recette->getId()]);
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            
            $commentaire->setUser($this->getUser());
            $commentaire->setRecette($recette);
            $this->em->persist($commentaire);
            $this->em->flush();
            return $this->redirectToRoute("recette.show", [
                'id' => $recette->getId(),
                'slug' => $recette->getSlug()
            ], 301);
            

        }

            if($recette->getSlug() !== $slug){
                return $this->redirectToRoute("recette.show", [
                    'id' => $recette->getId(),
                    'slug' => $recette->getSlug()
                ], 301);
            }
        return $this->render("recette/show.html.twig", [
            "recette" => $recette,
            "current_menu" => "recettes",
            "comments" => $comments,
            'form' => $form->createView()
            

        ]);
    }


    

}

?>