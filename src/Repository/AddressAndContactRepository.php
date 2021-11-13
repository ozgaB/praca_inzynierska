<?php

namespace App\Repository;

use App\Entity\UserBio\UserBio;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\AddressAndContact\AddressAndContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


class AddressAndContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddressAndContact::class);
    }
}
?>