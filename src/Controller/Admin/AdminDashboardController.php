<?php

namespace App\Controller\Admin;

use App\Entity\Liste;
use App\Entity\Objet;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    /**
     * @Route("/BabyAdmin", name="babyadmin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Babylist');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
		yield MenuItem::section('Users');
		yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
		yield MenuItem::section('Listes et Objets');
		yield MenuItem::linkToCrud('Listes', 'fa fa-heart', Liste::class);
		yield MenuItem::linkToCrud('Objets', 'fa fa-gifts', Objet::class);

    }

	// ...

	public function configureAssets(): Assets
	{
		return Assets::new()->addCssFile('css/admin.css');
	}
}
