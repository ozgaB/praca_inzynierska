<?php

namespace App\Repository;

use DietPlanAccess;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\DietPlan\DietPlanDay;
use App\Entity\DietPlan\DietPlanList;
use App\Entity\DietPlan\DietPlanProducts;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DietPlanDayRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DietPlanDay::class);
    }

    public function getDietPlanDayById($dietPlanDayId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'dietPlanDay.id AS id',
            'dietPlanDay.dayName',
            'dietPlanProduct.mealGroup AS mealGroup',
            'dietPlanProduct.productName AS productName',
            'dietPlanProduct.protein AS protein',
            'dietPlanProduct.carbo AS carbo',
            'dietPlanProduct.fat AS fat',
            'dietPlanProduct.kcl AS kcl',
        ])
        ->from('App\Entity\DietPlan\DietPlanDay','dietPlanDay')
        ->innerJoin('App\Entity\DietPlan\DietPlanList','dietPlanList', Join::WITH, 'dietPlanList.id = dietPlanDay.dietPlan')
        ->innerJoin('App\Entity\DietPlan\DietPlanProducts','dietPlanProduct', Join::WITH, 'dietPlanDay.id = dietPlanProduct.dietPlanDay')
        ->where($qb->expr()->eq('dietPlanDay.id',':id_day'))
        ->setParameter(':id_day',$dietPlanDayId);

        return $qb->getQuery()->getResult();
    }

    public function getDietPlanDayProductSumById($dietPlanDayId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'SUM(dietPlanProduct.protein) AS proteinSum',
            'SUM(dietPlanProduct.carbo) AS carboSum',
            'SUM(dietPlanProduct.fat) AS fatSum',
            'SUM(dietPlanProduct.kcl) AS kclSum',
        ])
        ->from('App\Entity\DietPlan\DietPlanDay','dietPlanDay')
        ->innerJoin('App\Entity\DietPlan\DietPlanList','dietPlanList', Join::WITH, 'dietPlanList.id = dietPlanDay.dietPlan')
        ->innerJoin('App\Entity\DietPlan\DietPlanProducts','dietPlanProduct', Join::WITH, 'dietPlanDay.id = dietPlanProduct.dietPlanDay')
        ->where($qb->expr()->eq('dietPlanDay.id',':id_day'))
        ->setParameter(':id_day',$dietPlanDayId);

        return $qb->getQuery()->getSingleResult();
    }

    public function getPreviousDay(int $dietPlanDayId, int $dietPlanId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select([
                'dietPlanDay.id'
                ])
            ->from('App\Entity\DietPlan\DietPlanDay','dietPlanDay')
            ->innerJoin('App\Entity\DietPlan\DietPlanList','dietPlanList', Join::WITH, 'dietPlanList.id = dietPlanDay.dietPlan')
            ->where('dietPlanDay.id < :dietPlanDayId')
            ->andWhere('dietPlanList.id = :dietPlanId')
            ->setParameter(':dietPlanDayId', $dietPlanDayId)
            ->setParameter(':dietPlanId', $dietPlanId)
            ->orderBy('dietPlanDay.id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getNextDay(int $dietPlanDayId, int $dietPlanId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select([
                'dietPlanDay.id'
                ])
            ->from('App\Entity\DietPlan\DietPlanDay','dietPlanDay')
            ->innerJoin('App\Entity\DietPlan\DietPlanList','dietPlanList', Join::WITH, 'dietPlanList.id = dietPlanDay.dietPlan')
            ->where('dietPlanDay.id > :dietPlanDayId')
            ->andWhere('dietPlanList.id = :dietPlanId')
            ->setParameter(':dietPlanDayId', $dietPlanDayId)
            ->setParameter(':dietPlanId', $dietPlanId)
            ->orderBy('dietPlanDay.id', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}


?>