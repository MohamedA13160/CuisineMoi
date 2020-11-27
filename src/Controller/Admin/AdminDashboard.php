<?php 

namespace App\Controller\Admin;


use App\Repository\CategoryRepository;
use App\Repository\CommentaireRepository;
use App\Repository\RecetteRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route ("/admin" , name="admin.")
 */

class AdminDashboard extends AbstractController
{


    /**
     * @Route ("/" , name="index")
     */
    public function index (RecetteRepository $recetteRepository, UserRepository $userRepository, CategoryRepository $categoryRepository, CommentaireRepository $commentaireRepository): Response
    {

        $recettes = $recetteRepository->findAll();
        $users = $userRepository->findAll();
        $categories = $categoryRepository->findAll();
        $commentaires = $commentaireRepository->findAll();
        return $this->render("admin/index.html.twig" ,[
            "current_menu" => "dash",
            "recettes" => count($recettes),
            "users" => count($users),
            "categories" => count($categories),
            "commentaires" => count($commentaires)
            
        ]);
    }


}
