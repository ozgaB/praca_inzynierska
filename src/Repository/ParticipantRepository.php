<?php

namespace App\Repository;

use App\Entity\Security\User;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\Participant\Participant;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function getParticipantsByTrainerId(int $trainerId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'participant.idUser',
            'user.firstName',
            'user.lastName',
        ])
        ->from('App\Entity\Participant\Participant','participant')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = participant.idUser')
        ->where($qb->expr()->in('participant.idTrainer',':id_trainer'))
        ->setParameter(':id_trainer',$trainerId);

        return $qb->getQuery()->getResult();
    }

    public function getTrainerWithDataByUserId(int $userId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'participant.idTrainer',
            'user.firstName',
            'user.lastName',
        ])
        ->from('App\Entity\Participant\Participant','participant')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = participant.idTrainer')
        ->where($qb->expr()->eq('participant.idUser',':id_user'))
        ->setParameter(':id_user',$userId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getParticipantsByTrainerIdWithoutAccess(int $trainerId,int $trainingPlanId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'participant.idUser',
            'user.firstName',
            'user.lastName',
        ])
        ->from('App\Entity\Participant\Participant','participant')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = participant.idUser')
        ->leftJoin('App\Entity\TrainingPlan\TrainingPlanAccess','access', Join::WITH, ':id_training_plan = access.idTrainingPlan')
        ->where($qb->expr()->andX($qb->expr()->eq('participant.idTrainer',':id_trainer'),$qb->expr()->isNull('access.idUser')))
        ->setParameter(':id_trainer',$trainerId)
        ->setParameter(':id_training_plan',$trainingPlanId);

        return $qb->getQuery()->getResult();
    }

}




?>