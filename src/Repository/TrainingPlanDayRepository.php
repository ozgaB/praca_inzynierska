<?php

namespace App\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TrainingPlan\TrainingPlanDay;
use App\Entity\TrainingPlan\TrainingPlanExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TrainingPlanDayRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingPlanDay::class);
    }

    public function getTrainingPlanDayById($trainingPlanDayId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'trainingPlanDay.id AS id',
            'trainingPlanDay.dayName',
            'trainingPlanExercise.muscleGroup AS muscleGroup',
            'trainingPlanExercise.exerciseName AS exerciseName',
            'trainingPlanExercise.sets AS sets',
            'trainingPlanExercise.repetition AS reps',
            'trainingPlanExercise.break AS break',
            'trainingPlanExercise.time AS time',
        ])
        ->from('App\Entity\TrainingPlan\TrainingPlanDay','trainingPlanDay')
        ->innerJoin('App\Entity\TrainingPlan\TrainingPlanList','trainingPlanList', Join::WITH, 'trainingPlanList.id = trainingPlanDay.trainingPlan')
        ->innerJoin('App\Entity\TrainingPlan\TrainingPlanExercise','trainingPlanExercise', Join::WITH, 'trainingPlanDay.id = trainingPlanExercise.trainingPlanDay')
        ->where($qb->expr()->eq('trainingPlanDay.id',':id_day'))
        ->setParameter(':id_day',$trainingPlanDayId);

        return $qb->getQuery()->getResult();
    }

    public function getPreviousDay(int $trainingPlanDayId, int $trainingPlanId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select([
                'trainingPlanDay.id'
                ])
            ->from('App\Entity\TrainingPlan\TrainingPlanDay','trainingPlanDay')
            ->innerJoin('App\Entity\TrainingPlan\TrainingPlanList','trainingPlanList', Join::WITH, 'trainingPlanList.id = trainingPlanDay.trainingPlan')
            ->where('trainingPlanDay.id < :trainingPlanDayId')
            ->andWhere('trainingPlanList.id = :trainingPlanId')
            ->setParameter(':trainingPlanDayId', $trainingPlanDayId)
            ->setParameter(':trainingPlanId', $trainingPlanId)
            ->orderBy('trainingPlanDay.id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getNextDay(int $trainingPlanDayId, int $trainingPlanId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select([
                'trainingPlanDay.id'
                ])
            ->from('App\Entity\TrainingPlan\TrainingPlanDay','trainingPlanDay')
            ->innerJoin('App\Entity\TrainingPlan\TrainingPlanList','trainingPlanList', Join::WITH, 'trainingPlanList.id = trainingPlanDay.trainingPlan')
            ->where('trainingPlanDay.id > :trainingPlanDayId')
            ->andWhere('trainingPlanList.id = :trainingPlanId')
            ->setParameter(':trainingPlanDayId', $trainingPlanDayId)
            ->setParameter(':trainingPlanId', $trainingPlanId)
            ->orderBy('trainingPlanDay.id', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}


?>