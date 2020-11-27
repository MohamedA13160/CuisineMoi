<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Recette;
use App\Entity\RecetteSearch;
use App\Form\CategoryType;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    public function findAllRecette($id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.autheur = :val')
            ->setParameter(':val', $id)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByCreated()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.created_at', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findAllEntrer()
    {
        return $this->createQueryBuilder('r')
            ->where('r.category = :id')
            ->setParameter('id', 20)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findAllPlat()
    {
        return $this->createQueryBuilder('r')
            ->where('r.category = :id')
            ->setParameter('id', 21)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findAllDessert()
    {
        return $this->createQueryBuilder('r')
            ->where('r.category = :id')
            ->setParameter('id', 22)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findAllBoisson()
    {
        return $this->createQueryBuilder('r')
            ->where('r.category = :id')
            ->setParameter('id', 23)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
   

    public function findAllVisibleQuery(RecetteSearch $search): Query
    {
        $query = $this->findVisibleQuery();
        

        if ($search->getNameRecette()){
            $query = $query
                        ->where('r.title = :name')
                        ->setParameter('name', $search->getNameRecette());
        }
        if($search->getOptions()->count() > 0){
            $key= 0;
            foreach ($search->getOptions() as $option) {
                $key++;
                $query = $query
                        ->andWhere(":option$key MEMBER OF r.options")
                        ->setParameter("option$key" , $option);

            }

        }
        return $query->getQuery();
   
          
    }
    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('r');
            
    }
    // /**
    //  * @return Recette[] Returns an array of Recette objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recette
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
