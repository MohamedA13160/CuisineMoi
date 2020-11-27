<?php 

namespace App\Controller\Admin;


use DateTime;
use App\Entity\User;
use App\Form\UserType;
use App\Form\FormUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @Route ("/admin" , name="admin.")
 */

class AdminUserController extends AbstractController
{

    
    public function __construct(UserRepository $repository, EntityManagerInterface $em)
    {
        
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route ("/user" , name="users.index")
     * 
     */
    public function usersList()
    {
        $users = $this->repository->findAll();
        return $this->render("admin/users/index.html.twig", [
            "current_menu_users" => "user",
            "users" => $users
        ]);
    }

    /**
     * @Route ("/user/{id}" , name="users.edit", methods="GET|POST")
     */
    

    public function userEdit(User $usere, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $usere);
        
        // Regarde la requete et l'ensemble des champs grace au setter de la propriéter Recette
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $message = "Modification réussis ! ";
            $this->em->flush();
            $this->addFlash('success', $message);
            return $this->redirectToRoute("admin.users.index", [], 301);
        }

        return $this->render("admin/users/edit.html.twig", [
                'user' => $usere,
                "current_menu_users" => "user",
                'form' => $form->createView()
            ]);
    }
    

    /**
     * @Route ("/user/{id}" , name="user.delete" , methods="DELETE")
     */

    public function delete(User $user, Request $request)
    {
        if ($this->isCsrfTokenValid('token_id', $request->get('_token'))) {
            $message = "Suppression réussis ! ";
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', $message);
        }

        return $this->redirectToRoute("admin.users.index", [], 301);
    }

}
?>