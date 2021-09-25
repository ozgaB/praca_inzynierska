<?php

namespace App\Utils;

use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;


class InvitationAfterAddParticipantRemover
{
    private EntityManagerInterface $entityManager;
    private InvitationRepository $invitationRepository;

    public function __construct(EntityManagerInterface $entityManager,InvitationRepository $invitationRepository)
    {
    $this->entityManager = $entityManager;
    $this->invitationRepository = $invitationRepository;
    }

    public function removeInvitationAfterAddParticipant($invitationId)
    {
        try{
            $invitation = $this->invitationRepository->findOneBy(['id' => $invitationId]);
            $this->entityManager->remove($invitation);
            $this->entityManager->flush();
            return true;
        }
        catch(exception $e)
        {
            return false;
        }
    }
}
?>