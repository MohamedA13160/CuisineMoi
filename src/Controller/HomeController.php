<?php

namespace App\Controller;

use DateTime;
use App\Entity\Contact;
use App\Entity\Recette;
use App\Form\ContactType;
use App\Notification\Notification;
use App\Repository\RecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{

    public RecetteRepository $repository;

    public function __construct(RecetteRepository $repository)
    {
      $this->repository = $repository;
    }
    /**
     * @Route ("/" , name ="home")
     */
    public function index() : Response
    {
      $recettes = $this->repository->findByCreated();
        
      return $this->render("pages/home.html.twig", [
        "recettes" => $recettes,
        "current_menu" => "menu"
          
      ]);
    }
  /**
   * @Route ("/contact" , name ="contact")
   */
  public function contact(Request $request, Notification $notification): Response
  {
    $contact = new Contact();
    $form = $this->createForm(ContactType::class, $contact);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $notification->notifyContact($contact);
        return $this->redirectToRoute('home', [], 301);

    }


    return $this->render("pages/contact.html.twig", [
      
      "current_menu_contact" => "contact",
      "form" => $form->createView()
    ]);
  }
}


?>