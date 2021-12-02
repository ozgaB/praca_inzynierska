<?php

namespace App\Repository;

use App\Entity\Security\User;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\MainPage\MainPageElement;

class MainPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MainPageElement::class);
    }

    public function getAllFromPageElement()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
        ->select([
            'mainPage.id',
            'mainPage.logoText',
            'mainPage.firstText',
            'mainPage.secondText',
            'mainPage.trainerSectionVisible',
        ])
        ->from('App\Entity\MainPage\MainPageElement','mainPage');

        return $qb->getQuery()->getOneOrNullResult();
    }
}
?>