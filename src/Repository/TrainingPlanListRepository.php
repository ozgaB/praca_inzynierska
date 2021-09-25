<?php

namespace App\Repository;

use TrainingPlanAccess;
use App\Entity\Security\User;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TrainingPlan\TrainingPlanList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TrainingPlanListRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingPlanList::class);
    }

    public function getTrainingPlanListByTrainerId(int $trainerId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'trainingPlanList.id AS id',
            'trainingPlanList.idTrainer AS idTrainer',
            'trainingPlanList.planName AS planName',
            'trainingPlanList.description AS description',
            'trainingPlanList.createdAt AS createdAt',
        ])
        ->from('App\Entity\TrainingPlan\TrainingPlanList','trainingPlanList')
        ->where($qb->expr()->eq('trainingPlanList.idTrainer',':id_trainer'))
        ->setParameter(':id_trainer',$trainerId);

        return $qb->getQuery()->getResult();
    }

    public function getTrainingPlanListByUserId(int $userId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'trainingPlanList.id AS id',
            'trainingPlanList.idTrainer AS idTrainer',
            'trainingPlanList.planName AS planName',
            'trainingPlanList.description AS description',
            'trainingPlanList.createdAt AS createdAt',
            'user.lastName AS trainerLastName',
            'user.firstName AS trainerFirstName',
            'MIN(trainingPlanDay.id) AS firstDay',
        ])
        ->from('App\Entity\TrainingPlan\TrainingPlanList','trainingPlanList')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = trainingPlanList.idTrainer')
        ->innerJoin('App\Entity\TrainingPlan\TrainingPlanAccess','access', Join::WITH, 'access.idTrainingPlan = trainingPlanList.id')
        ->innerJoin('App\Entity\TrainingPlan\TrainingPlanDay','trainingPlanDay', Join::WITH, 'trainingPlanList.id = trainingPlanDay.idTrainingPlan')
        ->where($qb->expr()->eq('access.idUser',':id_user'))
        ->setParameter(':id_user',$userId)
        ->groupBy('trainingPlanList.id');

        return $qb->getQuery()->getResult();
    }
    
    public function getDaysByTrainingPlanId(int $trainingPlanId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'trainingPlanList.id AS id',
            'trainingPlanDay.id AS trainingPlanDayId',
        ])
        ->from('App\Entity\TrainingPlan\TrainingPlanList','trainingPlanList')
        ->innerJoin('App\Entity\TrainingPlan\TrainingPlanDay','trainingPlanDay', Join::WITH, 'trainingPlanList.id = trainingPlanDay.idTrainingPlan')
        ->where($qb->expr()->eq('trainingPlanList.id',':id_training_plan'))
        ->setParameter(':id_training_plan',$trainingPlanId);

        return $qb->getQuery()->getResult();
    }
}


?>