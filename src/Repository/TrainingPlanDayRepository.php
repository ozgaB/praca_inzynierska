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
            'trainingPlanExercise.repetition AS repetition',
            'trainingPlanExercise.break AS break',
            'trainingPlanExercise.time AS time',
        ])
        ->from('App\Entity\TrainingPlan\TrainingPlanDay','trainingPlanDay')
        ->innerJoin('App\Entity\TrainingPlan\TrainingPlanExercise','trainingPlanExercise', Join::WITH, 'trainingPlanDay.id = trainingPlanExercise.trainingPlanDay')
        ->where($qb->expr()->eq('trainingPlanDay.id',':id_day'))
        ->setParameter(':id_day',$trainingPlanDayId);

        return $qb->getQuery()->getResult();
    }
}


?>