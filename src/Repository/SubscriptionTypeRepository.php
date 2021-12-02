<?php

namespace App\Repository;

use TrainingPlanAccess;
use App\Entity\DietPlan\DietPlanProducts;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TrainingPlan\TrainingPlanExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Subscription\SubscriptionType;

class SubscriptionTypeRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscriptionType::class);
    }

    public function getSubscriptionTypeList()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'SubscriptionType.id AS id',
            'SubscriptionType.name AS name',
            'SubscriptionType.description AS description',
            'SubscriptionType.lifeTimeInSeconds AS lifeTime',
            'SubscriptionType.pricePln AS pricePln',
            'SubscriptionType.priceEur AS priceEur',
        ])
        ->from('App\Entity\Subscription\SubscriptionType','SubscriptionType');

        return $qb->getQuery()->getResult();
    }

    
}


?>