<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\User;
use App\Form\ListeType;
use App\Form\SearchListesSharedType;
use App\Repository\ListeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class SharedListesController extends AbstractController
{
    /**
     * @Route("/shared/listes", name="shared_listes")
     */
    public function index(ListeRepository $listesRepository): Response
    {
		/** @var User $user */
		$user = $this->getUser();
		if ($user){
			//Get Listes byownerId
			$listes =$listesRepository->findByOwner($user);
			//Get Liste public
			$listespublic = $listesRepository->findPublicListes(1);
			if ($listespublic) {
				return $this->render('shared_listes/index.html.twig', [
					'controller_name' => 'HomeController',
					'listes' => $listes,
					'listes_public' => $listespublic,
				]);
			}
		}else{
			return $this->render('shared_listes/index.html.twig', [
				'controller_name' => 'HomeController',
				'listes' => "",
				'listes_public' => "",
			]);
		}
        return $this->render('shared_listes/index.html.twig', [
            'controller_name' => 'SharedListesController',
        ]);
    }

	/**
	 * @Route("/search/listes", name="search_listes" ,methods={"GET","POST"})
	 */
	public function searchListesByFamilles(ListeRepository $listesRepository, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
	{
		/** @var Liste $liste */
		$form = $this->createForm(SearchListesSharedType::class);
		$form->handleRequest($request);
		$user = $this->getUser();
		if ($user){
			if ($form->isSubmitted() && $form->isValid()) {
				$searchvalue = $form->get('famille')->getData();
				$listespublic = $listesRepository->findFamilleListes($searchvalue);
				if ($listespublic) {
					return $this->render('search_listes/index.html.twig', [
						'controller_name' => 'Search Listes',
						'listesform' => $form->createView(),
						'listes_public' => $listespublic,
						'error' => "",

					]);
				}else{
					return $this->render('search_listes/index.html.twig', [
						'controller_name' => 'Search Listes',
						'listesform' => $form->createView(),
						'listes_public' => $listespublic,
						'error' => "Votre recherche ne correspond a aucunes listes veuillez réessayer svp !",
					]);
				}
			}else{
				return $this->render('search_listes/index.html.twig', [
					'controller_name' => 'Search Listes',
					'listesform' => $form->createView(),
					'listes_public' => "",
					'error' => "",

				]);
			}
		}else{
			$this->addFlash('warning','Vous devez être enregistré avant de pouvoir éffectuer une recherche !');
			return $this->redirectToRoute("app_register");
		}

		//Get Liste public

	}
}
