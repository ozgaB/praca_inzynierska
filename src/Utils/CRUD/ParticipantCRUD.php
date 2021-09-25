<?php

namespace App\Utils\CRUD;

use Symfony\Component\Form\Form;
use App\Entity\Participant\Participant;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;

class ParticipantCRUD{

private EntityManagerInterface $entityManager;
private ParticipantRepository $participantRepository;

public function __construct(EntityManagerInterface $entityManager, ParticipantRepository $participantRepository)
{
    $this->entityManager = $entityManager;
    $this->participantRepository = $participantRepository;
}

public function addParticipantFromInvitation($user,$trainer)
{
    try{
        $participant = new Participant();
        $participant->setIdUser($user->getId());
        $participant->setIdTrainer($trainer->getId());
        $this->entityManager->persist($participant);
        $this->entityManager->flush();
        return true;
    }
    catch(Exception $e){

        return false;
    }

}

public function removeParticipant($user)
{
    try{
        $participant = $this->participantRepository->findOneBy(['idUser' => $user->getId()]);
        $this->entityManager->remove($participant);
        $this->entityManager->flush();
        return true;
    } catch(Exception $e){
        return false;
    }
}

}
?>