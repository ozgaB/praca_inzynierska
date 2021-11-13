<?php

namespace App\Repository;

use TrainingPlanAccess;
use App\Entity\DietPlan\DietPlanProducts;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TrainingPlan\TrainingPlanExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DietPlanProductRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DietPlanProducts::class);
    }

    
}


?>