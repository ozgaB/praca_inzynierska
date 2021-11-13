<?php

namespace App\Repository;

use App\Entity\TrainerBio\TrainerBio;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


class TrainerBioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainerBio::class);
    }
}
?>