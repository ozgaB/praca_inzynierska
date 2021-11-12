<?php

namespace App\Repository;

use App\Entity\Security\User;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\Invitation\Invitation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getUsersWithRoleTrainer()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'user.id',
            'user.firstName',
            'user.lastName',
            'user.email',
            'user.roles',
        ])
        ->from('App\Entity\Security\User','user')
        ->where($qb->expr()->in('user.roles',':user_role'))
        ->setParameter(':user_role','["ROLE_TRAINER"]');

        return $qb->getQuery()->getResult();
    }

    public function getUsersWithRoleTrainerAndCheckInvitation(int $userId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'user.id',
            'user.firstName',
            'user.lastName',
            'user.email',
            'user.roles',
            'invitation.id AS invitationId',
        ])
        ->from('App\Entity\Security\User','user')
        ->leftJoin('App\Entity\Invitation\Invitation','invitation', Join::WITH, $qb->expr()->andX($qb->expr()->eq('user.id','invitation.idTrainer'),$qb->expr()->eq('invitation.idUser',':user_id')))
        ->where($qb->expr()->eq('user.roles',':user_role'))
        ->setParameter(':user_role','["ROLE_TRAINER"]')
        ->setParameter(':user_id',$userId);

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
