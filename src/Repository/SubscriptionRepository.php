<?php

namespace App\Repository;

use TrainingPlanAccess;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\DietPlan\DietPlanProducts;
use App\Entity\Subscription\Subscription;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Subscription\SubscriptionType;
use App\Entity\TrainingPlan\TrainingPlanExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class SubscriptionRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function getSubscriptionByTrainerId(int $trainerId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'Subscription.id AS id',
            'Subscription.idTrainer AS idTrainer',
            'Subscription.createdAt AS createdAt',
            'Subscription.expireAt AS expireAt',
        ])
        ->from('App\Entity\Subscription\Subscription','Subscription')
        ->where($qb->expr()->eq('Susbcription.idTrainer',':id_trainer'))
        ->setParameter(':id_trainer',$trainerId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getAllSubscribers()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'Subscription.id AS id',
            'Subscription.idTrainer AS idTrainer',
            'Subscription.createdAt AS createdAt',
            'Subscription.expireAt AS expireAt',
            'User.firstName AS firstName',
            'User.lastName AS lastName',
            'User.email AS email'
        ])
        ->from('App\Entity\Subscription\Subscription','Subscription')
        ->innerJoin('App\Entity\Security\User','User', Join::WITH,$qb->expr()->eq('Subscription.idTrainer','User.id'));

        return $qb->getQuery()->getResult();
    } 
}


?>