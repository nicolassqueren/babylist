<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Repository\ObjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SharedListController extends AbstractController
{
    /**
     * @Route("/shared/list/{id}", name="shared_list")
	 * @param $id
	 * @param EntityManagerInterface $entityManager
	 * @param ObjetRepository $objetRepository
	 * @return Response
	 */
	public function index($id, EntityManagerInterface $entityManager, ObjetRepository $objetRepository): Response
	{
		/** @var Liste $liste */
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		return $this->render('shared_list/index.html.twig', [
			'controller_name' => 'ListeController',
			'liste' => $liste,
			'objets' => $objetRepository->findByListe($liste),
		]);
	}


	/**
	 * @Route ("/liste/{id}/shared", name="liste_shared")
	 * @param EntityManagerInterface $entityManager
	 * @param $id
	 * @param Request $request
	 * @return Response
	 */
	public function shared (EntityManagerInterface $entityManager, $id, Request $request) :Response{
		/** @var Liste $liste */
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		//Set shared liste by user
		$liste->setIsShared(true);
		//generate url for shared friends
		$baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
		$liste_url = $baseurl . "/shared/list/" . $liste->getId();
		$liste->setSharedUrl($liste_url);
		//set data
		$entityManager->persist($liste);
		$entityManager->flush();

		$this->addFlash('success','Congrats you shared your Liste !');

		return $this->redirectToRoute("shared_list", ["id"=>$liste->getId()]);
	}

	/**
	 * @Route ("/liste/{id}/close", name="liste_close")
	 * @param EntityManagerInterface $entityManager
	 * @param $id
	 * @param Request $request
	 * @return Response
	 */
	public function closeList (EntityManagerInterface $entityManager, $id, Request $request) :Response{
		/** @var Liste $liste */
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		$liste->setIsShared(false);
		$entityManager->persist($liste);
		$entityManager->flush();

		$this->addFlash('error','Warning you Closed your Liste !');

		return $this->redirectToRoute("liste", ["id"=>$liste->getId()]);
	}


}
