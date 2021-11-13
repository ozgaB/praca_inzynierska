<?php

namespace App\Repository;

use Doctrine\ORM\Query\Expr\Join;
use App\Entity\DietPlan\DietPlanAccess;
use App\Entity\DietPlan\DietPlanExercise;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DietPlanAccessRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DietPlanAccess::class);
    }

    public function getAccessListByDietPlanId(int $dietPlanId)
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
        ->from('App\Entity\DietPlan\DietPlanAccess','access')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = access.idUser')
        ->innerJoin('App\Entity\Participant\Participant','participant', Join::WITH, 'participant.idUser = access.idUser')
        ->where($qb->expr()->eq('access.idDietPlan',':id_diet_plan'))
        ->setParameter(':id_diet_plan',$dietPlanId);

        return $qb->getQuery()->getResult();
    }
    
}


?>