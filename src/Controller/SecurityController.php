<?php 

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;




class SecurityController extends AbstractController
{


    /**
     * @Route ("/login" , name="login") 
     * 
     */

    public function login(AuthenticationUtils $authenticationUtils)
    {
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        
        
       return $this->render('security/login.html.twig', [
           'last_username' => $lastUsername,
            "form_loge" => "loginUser",
           'error' => $error
       ]);
   
    }
    /**
     * @Route ("/login_success" , name="login.success") 
     * 
     */
    public function loginSuccess()
    {
      
       if ($this->isGranted("ROLE_ADMIN")) {
            
            return $this->redirectToRoute("admin.index", [], 301);
        }
        return $this->redirectToRoute("home");
            
        
        
        
        
        
    }


}   


?>