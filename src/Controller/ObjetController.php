<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Objet;
use App\Form\ObjetType;
use App\Repository\ObjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class ObjetController extends AbstractController
{
    /**
     * @Route("/objet", name="objet")
     */
    public function index(ObjetRepository $objetRepository): Response
    {
        return $this->render('objet/index.html.twig', [
            'controller_name' => 'ObjetController',
			'objets' => $objetRepository->findAll(),
        ]);
    }


	/**
	 * @Route("/liste/{id}/new", name="objet_new", methods={"GET","POST"})
	 * @param Request $request
	 * @return Response
	 */
	public function new(Request $request, $id, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
	{
		/** @var Liste $liste */
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		$objet = new Objet();
		$form = $this->createForm(ObjetType::class, $objet);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$imagefile = $form->get('image_file')->getData();
			if ($imagefile){
				$originalFilename = pathinfo($imagefile->getClientOriginalName(), PATHINFO_FILENAME);
				$safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imagefile->guessExtension();
				try {
					$imagefile->move(
						$this->getParameter('kernel.project_dir') . '/public'. $this->getParameter('objet_directory'),
                        $newFilename
					);
				}catch (FileException $exception){
					throw new FileException('Désolé votre image n a pas pu être crée !');
				}
				$objet->setImage($this->getParameter('objet_directory') . "/" .$newFilename);
			}
			$objet->setListe($liste);
			$entityManager->persist($objet);
			$entityManager->flush();

			return $this->redirectToRoute("liste", ["id"=>$liste->getId()]);
		}

		return $this->render('objet/new.html.twig', [
			'objet' => $objet,
			'liste' => $liste,
			'objetform' => $form->createView(),
		]);
	}

	/**
	 * @Route("/liste/{id}/edit/{objetId}", name="objet_edit", methods={"GET","POST"})
	 * @param Request $request
	 * @param $id
	 * @param $objetId
	 * @param EntityManagerInterface $entityManager
	 * @param SluggerInterface $slugger
	 * @return Response
	 */
	public function edit(Request $request, $id, $objetId, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
	{
		/** @var Liste $liste */
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		$objet = $entityManager->getRepository(Objet::class)->find($objetId);
		$form = $this->createForm(ObjetType::class, $objet);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$imagefile = $form->get('image_file')->getData();
			if ($imagefile){
				$originalFilename = pathinfo($imagefile->getClientOriginalName(), PATHINFO_FILENAME);
				$safeFilename = $slugger->slug($originalFilename);
				$newFilename = $safeFilename.'-'.uniqid().'.'.$imagefile->guessExtension();
				try {
					$imagefile->move(
						$this->getParameter('kernel.project_dir') . '/public'. $this->getParameter('objet_directory'),
						$newFilename
					);
				}catch (FileException $exception){
					throw new FileException('Désolé votre image n a pas pu être crée !');
				}
				$objet->setImage($this->getParameter('objet_directory') . "/" .$newFilename);
			}
			$objet->setListe($liste);
			$entityManager->persist($objet);
			$entityManager->flush();

			return $this->redirectToRoute("liste", ["id"=>$liste->getId()]);
		}

		return $this->render('objet/new.html.twig', [
			'objet' => $objet,
			'liste' => $liste,
			'objetform' => $form->createView(),
		]);
	}
	/**
	 * @Route("/liste/{id}/delete/{objetId}", name="objet_delete", methods={"GET"})
	 * @param Request $request
	 * @param $id
	 * @param $objetId
	 * @return Response
	 */
	public function delete (EntityManagerInterface $entityManager, $id, $objetId, Request $request) :Response{
		/** @var Objet $objet */
		$objet = $entityManager->getRepository(Objet::class)->find($objetId);
		/** @var Liste $liste */
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		$entityManager->remove($objet);
		$entityManager->flush();

		return $this->redirectToRoute("liste", ["id"=>$liste->getId()]);

	}

}
