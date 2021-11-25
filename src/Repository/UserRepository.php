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

    public function getUsersByRole(string $role)
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
        ->where($qb->expr()->eq('user.roles',':user_role'))
        ->setParameter(':user_role',$role);

        return $qb->getQuery()->getResult();
    }

    
    // FILTERS

    public function getAllUsersQB()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'user.id AS id',
            'user.firstName AS firstName',
            'user.lastName AS lastName',
            'user.email AS email',
            'user.roles AS roles',
        ])
        ->from('App\Entity\Security\User','user');

        return $qb;
    }

    public function getUserRoleFilter($qb,$role)
    {
        $qb
        ->andWhere($qb->expr()->eq('user.roles',':user_role'))
        ->setParameter(':user_role',$role);

        return $qb;
    }

    public function getUserFirstNameFilter($qb,$firstName)
    {
        $qb
        ->andWhere($qb->expr()->like('user.firstName',':user_first_name'))
        ->setParameter(':user_first_name','%'.$firstName.'%');

        return $qb;
    }

    public function getUserLastNameFilter($qb,$lastName)
    {
        $qb
        ->andWhere($qb->expr()->like('user.lastName',':user_last_name'))
        ->setParameter(':user_last_name','%'.$lastName.'%');

        return $qb;
    }

    public function getUserEmailFilter($qb,$email)
    {
        $qb
        ->andWhere($qb->expr()->like('user.email',':user_email'))
        ->setParameter(':user_email','%'.$email.'%');

        return $qb;
    }

    public function getAllUserInActive()
    {
        $qb = $this->getAllUsersQB();
        $qb
        ->andWhere($qb->expr()->like('user.isActive',':is_active'))
        ->setParameter(':is_active',false);

        return $qb;
    }

}
