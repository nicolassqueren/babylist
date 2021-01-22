<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Form\ListeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateListeController extends AbstractController
{
    /**
     * @Route("/create/liste", name="create_liste")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

		$user = $this->getUser();
		if ($user){
			$liste = new Liste();
			$listeForm = $this->createForm(ListeType::class, $liste);
			$listeForm->handleRequest($request);
			if ($listeForm->isSubmitted() ){
				if ($listeForm->isValid()){
					dump($listeForm);
				}else{
					dump('Not valid Malin');
				}

//    		return $this->redirectToRoute("my_first_template");
			}
		}else{
			echo "Redirect to another route";
		}
        return $this->render('create_liste/index.html.twig', [
            'listeform' => $listeForm->createView()
        ]);
    }
}
