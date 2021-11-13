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

    public function getParticipantsByTrainerIdWithoutAccessToTrainingPlan(int $trainerId,int $trainingPlanId)
    {
        $em = $this->getEntityManager();

        $sub = $em->createQueryBuilder();
        $sub->select("access.idUser");
        $sub->from('App\Entity\TrainingPlan\TrainingPlanAccess','access');
        $sub->andWhere('access.idTrainingPlan = :id_training_plan AND access.idUser = participant.idUser');


        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'participant.idUser',
            'user.firstName',
            'user.lastName',
        ])
        ->from('App\Entity\Participant\Participant','participant')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = participant.idUser')
        ->where($qb->expr()->andX($qb->expr()->eq('participant.idTrainer',':id_trainer')))
        ->andWhere($qb->expr()->not($qb->expr()->exists($sub->getDQL())))
        ->setParameter(':id_trainer',$trainerId)
        ->setParameter(':id_training_plan',$trainingPlanId);

        return $qb->getQuery()->getResult();
    }

    public function getParticipantsByTrainerIdWithoutAccessToDietPlan(int $trainerId,int $dietPlanId)
    {
        $em = $this->getEntityManager();

        $sub = $em->createQueryBuilder();
        $sub->select("access.idUser");
        $sub->from('App\Entity\DietPlan\DietPlanAccess','access');
        $sub->andWhere('access.idDietPlan = :id_diet_plan AND access.idUser = participant.idUser');


        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'participant.idUser',
            'user.firstName',
            'user.lastName',
        ])
        ->from('App\Entity\Participant\Participant','participant')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = participant.idUser')
        ->where($qb->expr()->andX($qb->expr()->eq('participant.idTrainer',':id_trainer')))
        ->andWhere($qb->expr()->not($qb->expr()->exists($sub->getDQL())))
        ->setParameter(':id_trainer',$trainerId)
        ->setParameter(':id_diet_plan',$dietPlanId);

        return $qb->getQuery()->getResult();
    }

    public function getCountOfActualTrainerParticipants($trainerId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'Count(participant.id) AS count_of_trainer_participants',
        ])
        ->from('App\Entity\Participant\Participant','participant')
        ->where($qb->expr()->in('participant.idTrainer',':id_trainer'))
        ->setParameter(':id_trainer',$trainerId);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getParticipantsWithAccessPlan(int $trainerId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'participant.idUser',
            'user.firstName AS firstName',
            'user.lastName AS lastName',
            'trainingPlanList.planName AS planName',
            'access.id AS idAccess',
            'trainingPlanList.id AS idTrainingPlan',
        ])
        ->from('App\Entity\Participant\Participant','participant')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = participant.idUser')
        ->innerJoin('App\Entity\TrainingPlan\TrainingPlanAccess','access', Join::WITH, 'access.idUser = participant.idUser')
        ->innerJoin('App\Entity\TrainingPlan\TrainingPlanList','trainingPlanList', Join::WITH, 'trainingPlanList.id = access.idTrainingPlan')
        ->where($qb->expr()->eq('participant.idTrainer',':id_trainer'))
        ->setParameter(':id_trainer',$trainerId);

        return $qb->getQuery()->getResult();
    }

    public function getParticipantWithAccessPlan(int $trainerId, int $userId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'participant.idUser',
            'user.firstName AS firstName',
            'user.lastName AS lastName',
            'trainingPlanList.planName AS planName',
            'access.id AS idAccess',
            'trainingPlanList.id AS idTrainingPlan',
        ])
        ->from('App\Entity\Participant\Participant','participant')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = :id_user')
        ->innerJoin('App\Entity\TrainingPlan\TrainingPlanAccess','access', Join::WITH, 'access.idUser = :id_user')
        ->innerJoin('App\Entity\TrainingPlan\TrainingPlanList','trainingPlanList', Join::WITH, 'trainingPlanList.id = access.idTrainingPlan')
        ->where($qb->expr()->eq('participant.idTrainer',':id_trainer'))
        ->setParameter(':id_trainer',$trainerId)
        ->setParameter(':id_user', $userId)
        ->groupBy('access.id');

        return $qb->getQuery()->getResult();
    }

}




?>