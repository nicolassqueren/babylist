<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Objet;
use App\Entity\User;
use App\Form\ListeType;
use App\Form\ObjetType;
use App\Repository\ListeRepository;
use App\Repository\ObjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ListeController extends AbstractController
{
	/**
	 * @Route("/liste/{id}", name="liste")
	 * @param $id
	 * @param EntityManagerInterface $entityManager
	 * @param ObjetRepository $objetRepository
	 * @return Response
	 */
    public function index($id, EntityManagerInterface $entityManager, ObjetRepository $objetRepository): Response
    {
    	/** @var Liste $liste */
		$liste = $entityManager->getRepository(Liste::class)->find($id);
        return $this->render('liste/index.html.twig', [
            'controller_name' => 'ListeController',
			'liste' => $liste,
			'objets' => $objetRepository->findByListe($liste),
        ]);
    }

	/**
	 * * @Route("/listes", name="listes")
	 */
    public function listes (ListeRepository $listesRepository){
		/** @var User $user */
		$user = $this->getUser();
		if ($user){
			//TODO Ne récupérer que les listes de l'utilisateur OwnerId
			$listes =$listesRepository->findByOwner($user);
			return $this->render('listes/index.html.twig', [
				'controller_name' => 'HomeController',
				'listes' => $listes,
			]);
		}
	}

	/**
	 * @Route("/liste/{id}/edit", name="liste_edit",methods={"GET","POST"})
	 */
	public function edit (Request $request, $id, EntityManagerInterface $entityManager, SluggerInterface $slugger):Response
	{
		/** @var Liste $liste */
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		$form = $this->createForm(ListeType::class, $liste);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$imagefile = $form->get('image_file')->getData();
			if ($imagefile){
				$originalFilename = pathinfo($imagefile->getClientOriginalName(), PATHINFO_FILENAME);
				$safeFilename = $slugger->slug($originalFilename);
				$newFilename = $safeFilename.'-'.uniqid().'.'.$imagefile->guessExtension();
				try {
					$imagefile->move(
						$this->getParameter('kernel.project_dir') . '/public'. $this->getParameter('liste_directory'),
						$newFilename
					);
				}catch (FileException $exception){
					throw new FileException('Désolé votre image n a pas pu être crée !');
				}
				$liste->setImage($this->getParameter('liste_directory') . "/" .$newFilename);
			}
			$entityManager->persist($liste);
			$entityManager->flush();
			return $this->redirectToRoute("liste", ["id"=>$liste->getId()]);
		}

		return $this->render('create_liste/index.html.twig', [
			'listeform' => $form->createView()
		]);
	}


	/**
	 * @Route ("/liste/{id}/delete", name="liste_delete", methods={"GET"})
	 * @param EntityManagerInterface $entityManager
	 * @param $id
	 * @param Request $request
	 * @return Response
	 */
	public function delete (EntityManagerInterface $entityManager, $id, Request $request) :Response{
		/** @var Liste $liste */
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		$entityManager->remove($liste);
		$entityManager->flush();

		return $this->redirectToRoute("listes");

	}


}
