<?php

namespace App\Repository;

use DietPlanAccess;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\DietPlan\DietPlanDay;
use App\Entity\DietPlan\DietPlanList;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DietPlanListRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DietPlanList::class);
    }

    public function getDietPlanListByTrainerId(int $trainerId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'dietPlanList.id AS id',
            'dietPlanList.idTrainer AS idTrainer',
            'dietPlanList.planName AS planName',
            'dietPlanList.description AS description',
            'dietPlanList.createdAt AS createdAt',
            'MIN(dietPlanDay.id) AS firstDay',
        ])
        ->from('App\Entity\DietPlan\DietPlanList','dietPlanList')
        ->leftJoin('App\Entity\DietPlan\DietPlanDay','dietPlanDay', Join::WITH, 'dietPlanList.id = dietPlanDay.dietPlan')
        ->where($qb->expr()->eq('dietPlanList.idTrainer',':id_trainer'))
        ->setParameter(':id_trainer',$trainerId)
        ->groupBy('dietPlanList.id');

        return $qb->getQuery()->getResult();
    }

    public function getDietPlanListByUserId(int $userId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'dietPlanList.id AS id',
            'dietPlanList.idTrainer AS idTrainer',
            'dietPlanList.planName AS planName',
            'dietPlanList.description AS description',
            'dietPlanList.createdAt AS createdAt',
            'user.lastName AS trainerLastName',
            'user.firstName AS trainerFirstName',
            'MIN(dietPlanDay.id) AS firstDay',
        ])
        ->from('App\Entity\DietPlan\DietPlanList','dietPlanList')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = dietPlanList.idTrainer')
        ->innerJoin('App\Entity\DietPlan\DietPlanAccess','access', Join::WITH, 'access.idDietPlan = dietPlanList.id')
        ->innerJoin('App\Entity\DietPlan\DietPlanDay','dietPlanDay', Join::WITH, 'dietPlanList.id = dietPlanDay.dietPlan')
        ->where($qb->expr()->eq('access.idUser',':id_user'))
        ->setParameter(':id_user',$userId)
        ->groupBy('dietPlanList.id');

        return $qb->getQuery()->getResult();
    }
    
    public function getDaysByDietPlanId(int $dietPlanId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'dietPlanList.id AS id',
            'dietPlanDay.id AS dietPlanDayId',
        ])
        ->from('App\Entity\DietPlan\DietPlanList','dietPlanList')
        ->innerJoin('App\Entity\DietPlan\DietPlanDay','dietPlanDay', Join::WITH, 'dietPlanList.id = dietPlanDay.dietPlan')
        ->where($qb->expr()->eq('dietPlanList.id',':id_diet_plan'))
        ->setParameter(':id_diet_plan',$dietPlanId);

        return $qb->getQuery()->getResult();
    }
    
}


?>