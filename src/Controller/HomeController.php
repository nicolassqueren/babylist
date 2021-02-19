<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\User;
use App\Repository\ListeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index( ListeRepository $listesRepository): Response
    {
    	/** @var User $user */
    	$user = $this->getUser();
    	if ($user){
    		//TODO Ne récupérer que les listes de l'utilisateur OwnerId
			$listes =$listesRepository->findByOwner($user);
			return $this->render('home/index.html.twig', [
				'controller_name' => 'HomeController',
				'listes' => $listes,
			]);
		}else{
			return $this->render('home/index.html.twig', [
				'controller_name' => 'HomeController',
				'listes' => "",
			]);
		}
    }


}
