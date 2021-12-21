<?php

namespace App\Repository;

use TrainingPlanAccess;
use App\Entity\Payment\Payment;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\DietPlan\DietPlanProducts;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Subscription\SubscriptionType;
use App\Entity\TrainingPlan\TrainingPlanExercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class PaymentRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscriptionType::class);
    }
    
    public function getUserOrders(int $userId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'SubscriptionType.id AS idSubscriptionType',
            'SubscriptionType.name AS subscriptionTypeName',
            'SubscriptionType.description AS subscriptionTypeDescription',
            'SubscriptionType.pricePln AS subscriptionTypePricePln',
            'Payment.status AS paymentStatus',
            'Payment.idOrder AS idOrder',
            'Payment.idUser AS idUser',
            'Payment.id AS idPayment',

        ])
        ->from('App\Entity\Payment\Payment','Payment')
        ->innerJoin('App\Entity\Subscription\SubscriptionType','SubscriptionType', Join::WITH,$qb->expr()->eq('Payment.idSubscriptionType','SubscriptionType.id'))
        ->where($qb->expr()->eq('Payment.idUser',':id_user'))
        ->setParameter(':id_user',$userId); 

        return $qb->getQuery()->getResult();
    }
}
?>