<?php

namespace App\Controller\Admin;

use App\Entity\Liste;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ListeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Liste::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('famille'),
			TextField::new('description'),
			TextField::new('children'),
			AssociationField::new('owner', 'Propriétaire'),
			AssociationField::new('objets', 'Objets'),
			BooleanField::new('is_shared'),
        ];
    }

}
