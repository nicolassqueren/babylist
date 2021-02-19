<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Objet;
use App\Form\ObjetType;
use App\Repository\ObjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
		$liste = $entityManager->getRepository(Liste::class)->find($id);
        return $this->render('liste/index.html.twig', [
            'controller_name' => 'ListeController',
			'liste' => $liste,
			'objets' => $objetRepository->findByListe($liste),
        ]);
    }


}
