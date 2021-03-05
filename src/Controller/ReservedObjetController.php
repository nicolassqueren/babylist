<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Objet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ReservedObjetController extends AbstractController
{
    /**
	 * @Route("/liste/{id}/reserved/{objetId}", name="objet_reserved", methods={"GET","POST"})
     */
    public function index(Request $request, $id, $objetId, EntityManagerInterface $entityManager): Response
    {
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		$objet = $entityManager->getRepository(Objet::class)->find($objetId);
		$user = $this->getUser();
		//Check if user is register !
		if ($user){
			$objet->setReservedBy($user);
			$objet->setListe($liste);
			$entityManager->persist($objet);
			$entityManager->flush();
			$this->addFlash('success','Merci pour votre reservation !');
			return $this->redirectToRoute("shared_list", ["id"=>$liste->getId()]);
		}else{
			$this->addFlash('warning','Vous devez être enregistré avant de pouvoir réserver !');
			return $this->redirectToRoute("app_register");
		}
    }

	/**
	 * @Route("/liste/{id}/cancelreserved/{objetId}", name="objet_cancel_reserved", methods={"GET","POST"})
	 */
	public function cancelReserved(Request $request, $id, $objetId, EntityManagerInterface $entityManager): Response
	{
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		$objet = $entityManager->getRepository(Objet::class)->find($objetId);
		$user = $this->getUser();
		if ($user){
			$objet->setReservedBy(null);
			$objet->setListe($liste);
			$entityManager->persist($objet);
			$entityManager->flush();
			$this->addFlash('warning','Vous avez annuler votre réservation');

			return $this->redirectToRoute("shared_list", ["id"=>$liste->getId()]);
		}
		else{
			$this->addFlash('warning','Vous devez être enregistré avant de pouvoir réserver !');
			return $this->redirectToRoute("app_register");
		}
	}
}

