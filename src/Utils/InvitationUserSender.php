<?php

namespace App\Utils;

use App\Entity\Invitation\Invitation;
use Doctrine\ORM\EntityManagerInterface;

class InvitationUserSender{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
    $this->entityManager = $entityManager;
    }

    public function sendInvitation($currentUser,$trainer)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $invitation = new Invitation();
            $invitation->setIdUser($currentUser->getId());
            $invitation->setIdTrainer($trainer->getId());
            $invitation->setIsSend(true);
            $this->entityManager->persist($invitation);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return true;
        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
            return false;
        }
    }
}
?>