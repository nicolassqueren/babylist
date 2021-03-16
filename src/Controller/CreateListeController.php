<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Form\ListeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CreateListeController extends AbstractController
{
    /**
     * @Route("/create/liste", name="create_liste")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

		$user = $this->getUser();
		if ($user){
			$liste = new Liste();
			$listeForm = $this->createForm(ListeType::class, $liste);
			$listeForm->handleRequest($request);
			if ($listeForm->isSubmitted() ){
				//if forms list is valid
				if ($listeForm->isValid()){
					$imagefile = $listeForm->get('image_file')->getData();
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
					//Get user for liste and set Owner_id
					$liste->setOwner( $this->getUser());
					$liste->setIsShared(0);
					$entityManager->persist($liste);
					$entityManager->flush();
					return $this->redirectToRoute("liste", ["id"=>$liste->getId()]);
				}else{
					dump('Not valid Malin');
				}
			}
		}else{
			$this->addFlash('warning','Vous devez être enregistré avant de pouvoir Créer votre liste !');
			return $this->redirectToRoute("app_register");
		}
		return $this->render('create_liste/index.html.twig', [
			'listeform' => $listeForm->createView()
		]);
    }
}
