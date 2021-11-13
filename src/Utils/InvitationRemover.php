<?php
namespace App\Utils;


use Symfony\Component\Form\Form;
use App\Entity\Invitation\Invitation;
use App\Entity\Participant\Participant;
use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;

class InvitationRemover{

private EntityManagerInterface $entityManager;
private InvitationRepository $invitationRepository;

public function __construct(EntityManagerInterface $entityManager, InvitationRepository $invitationRepository)
{
    $this->entityManager = $entityManager;
    $this->invitationRepository = $invitationRepository;
}

public function removeInvitation(int $invitationId)
{
    try{
        $invitation = $this->invitationRepository->findOneBy(['id' => $invitationId]);
        $this->entityManager->remove($invitation);
        $this->entityManager->flush();
        return true;
    } catch(Exception $e){
        return false;
    }
}

}
?>