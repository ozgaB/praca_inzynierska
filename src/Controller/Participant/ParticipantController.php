<?php

namespace App\Controller\Participant;

use App\Entity\Security\User;
use App\Utils\CRUD\ParticipantCRUD;
use App\Entity\Invitation\Invitation;
use App\Repository\InvitationRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\InvitationAfterAddParticipantRemover;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ParticipantController extends AbstractController
{
    /**
     * @Route("/add_participant/{user}/invitation/{invitation}", name="add_participant")
     * 
     */
    public function addParticipant(User $user,Invitation $invitation, ParticipantCRUD $participantCRUD, Security $security,InvitationAfterAddParticipantRemover $invitationRemover)
    {
        $invitationId = $invitation->getId();
        $currentUserTrainer = $security->getUser();
        if(($participantCRUD->addParticipantFromInvitation($user,$currentUserTrainer))&&($invitationRemover->removeInvitationAfterAddParticipant($invitationId))){
            $this->addFlash('success', 'WOW dodałeś nowego uczestnika!');
        } else{
            $this->addFlash('danger', 'Nie udało się dodać nowego uczestnika!');
        }
        return $this->redirectToRoute('trainer_users');
    }
    /**
     * @Route("/remove_participant/{user}", name="remove_participant")
     */
    public function removeParticipant(User $user,ParticipantCRUD $participantCRUD)
    {
        $participantFirstName = $user->getFirstName();
        $participantLastName = $user->getLastName();
        $message = 'Użytkownik '.$participantFirstName.' '.$participantLastName.' został usunięty ';
        if($participantCRUD->removeParticipant($user)){
            $this->addFlash('success', $message);
        } else{
            $this->addFlash('danger', 'Nie udało się odpiąć uczestnika!');
        }
        return $this->redirectToRoute('trainer_users');
    }
    /**
     * @Route("/remove_participant_by_user/{user}/{actualTrainer}", name="remove_participant_by_user")
     */
    public function removeParticipantByUser(User $user, User $actualTrainer, ParticipantCRUD $participantCRUD)
    {
        $trainerFirstName = $actualTrainer->getFirstName();
        $trainerLastName = $actualTrainer->getLastName();
        $message = 'Trener '.$trainerFirstName.' '.$trainerLastName.' został poprawnie odpięty, teraz możesz wysłac nowe zaproszenia';
        if($participantCRUD->removeParticipant($user)){
            $this->addFlash('success', $message);
        } else{
            $this->addFlash('danger', 'Nie udało się odpiąć trenera!');
        }
        return $this->redirectToRoute('user_trainers');
    }
}


?>