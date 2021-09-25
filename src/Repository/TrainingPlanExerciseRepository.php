<?php

namespace App\Repository;

use TrainingPlanAccess;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TrainingPlan\TrainingPlanExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TrainingPlanExerciseRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingPlanExercise::class);
    }

    
}


?>