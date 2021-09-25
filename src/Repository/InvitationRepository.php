<?php

namespace App\Repository;

use App\Entity\Security\User;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\Invitation\Invitation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    public function getInvitationsByTrainerId(int $trainerId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'invitation.id AS id_invitation',
            'invitation.idUser',
            'user.firstName',
            'user.lastName',
        ])
        ->from('App\Entity\Invitation\Invitation','invitation')
        ->innerJoin('App\Entity\Security\User','user', Join::WITH, 'user.id = invitation.idUser')
        ->where($qb->expr()->in('invitation.idTrainer',':id_trainer'))
        ->setParameter(':id_trainer',$trainerId);

        return $qb->getQuery()->getResult();
    }
}


?>