<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Home extends AbstractController
{
    public function __construct()
    {
        
    }

    #[Route('/home')]
    public function home()
    {
        $data = [ 
                  'family_name'=>$_SESSION['family_name'], 
                  'given_name' => $_SESSION['given_name'],
                ];
        return $this->render('home.html.twig', $data);
    }
}