<?php

namespace App\Repository;

use App\Entity\Security\User;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TrainingPlan\TrainingPlanAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TrainingPlanAccessRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingPlanAccess::class);
    }

    public function getAccessListByTrainingPlanId(int $trainingPlanId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'access.id AS idAccess',
            'access.idUser AS idUser',
            'user.firstName AS firstName',
            'user.lastName AS lastName',
        ])
        ->from('App\Entity\TrainingPlan\TrainingPlanAccess','access')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = access.idUser')
        ->innerJoin('App\Entity\Participant\Participant','participant', Join::WITH, 'participant.idUser = access.idUser')
        ->where($qb->expr()->eq('access.idTrainingPlan',':id_training_plan'))
        ->setParameter(':id_training_plan',$trainingPlanId);

        return $qb->getQuery()->getResult();
    }
    
}


?>