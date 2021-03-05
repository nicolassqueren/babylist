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
    		//Get Listes byownerId
			$listes =$listesRepository->findByOwner($user);
			//Get Liste public
			$listespublic = $listesRepository->findPublicListes(1);
			if ($listes) {
				return $this->render('home/index.html.twig', [
					'controller_name' => 'HomeController',
					'listes' => $listes,
				]);
			}else{
				return $this->render('home/index.html.twig', [
					'controller_name' => 'HomeController',
					'listes' => $listes,
					'listes_public' => $listespublic,
				]);
			}
		}else{
			return $this->render('home/index.html.twig', [
				'controller_name' => 'HomeController',
				'listes' => "",
				'listes_public' => "",
			]);
		}
    }


}
