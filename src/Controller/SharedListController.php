<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Repository\ObjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
	public function shared (EntityManagerInterface $entityManager, $id, Request $request, MailerInterface $mailer) :Response{

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

		$this->addFlash('success','Félicitations vous venez de partager votre liste !');

		return $this->redirectToRoute("shared_list", ["id"=>$liste->getId()]);
	}

	/**
	 * @Route ("/liste/{id}/close", name="liste_close")
	 * @param EntityManagerInterface $entityManager
	 * @param $id
	 * @param Request $request
	 * @return Response
	 */
	public function closeList (EntityManagerInterface $entityManager, $id, Request $request, MailerInterface $mailer,  ObjetRepository $objetRepository) :Response{
		$user = $this->getUser();
		/** @var Liste $liste */
		$liste = $entityManager->getRepository(Liste::class)->find($id);
		$liste->setIsShared(false);
		$entityManager->persist($liste);
		$entityManager->flush();
		$this->addFlash('error','Vous venez de clore votre liste ! Félicitations :) ! Un email viens de vous être envoyé pour le récapitulatif');
		//Send mail too user for close liste action
		$email = (new TemplatedEmail())
			->from('mybabylistefr@gmail.com')
			->to($user->getEmail())
			->subject('Fermeture de votre liste !')
			->htmlTemplate('emails/close_liste.html.twig')
			->context([
				'user' => $user,
				'liste' => $liste,
				'objets' => $objetRepository->findByListe($liste),
			]);

		$mailer->send($email);
		return $this->redirectToRoute("liste", ["id"=>$liste->getId()]);
	}


}
