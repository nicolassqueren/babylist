<?php

namespace App\Repository;

use App\Entity\Liste;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Liste|null find($id, $lockMode = null, $lockVersion = null)
 * @method Liste|null findOneBy(array $criteria, array $orderBy = null)
 * @method Liste[]    findAll()
 * @method Liste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Liste[]    findByOwner(User $owner, array $orderBy = null, $limit = null, $offset = null)
 */
class ListeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Liste::class);
    }

     /**
      * @return Liste[] Returns an array of Liste objects
      */
    public function findPublicListes($value)
    {
        return $this->createQueryBuilder('listes')
            ->andWhere('listes.is_Shared = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

	/**
	 * @return Liste[] Returns an array of Liste objects
	 */
	public function findFamilleListes($value)
	{
		return $this->createQueryBuilder('listes')
			->andWhere('listes.famille = :val')
			->setParameter('val', $value)
			->getQuery()
			->getResult()
			;
	}

    /*
    public function findOneBySomeField($value): ?Liste
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
