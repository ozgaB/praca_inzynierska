<?php
namespace App\Controller\TrainerUser;

use App\Repository\InvitationRepository;
use App\Repository\ParticipantRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class TrainerUserController extends AbstractController
{
    /**
    *@Route("/trainer_home_page", name="trainer_home_page")
    *@Template()
    */
    public function index()
    {
        return [

        ];
    }

    /**
     * @Route("/trainer_users", name="trainer_users")
     * @Template()
     */
    public function trainerUsers(Security $security, InvitationRepository $invitationRepository, ParticipantRepository $participantRepository)
    {
        $trainerId = $security->getUser()->getId();
        $invitationList = $invitationRepository->getInvitationsByTrainerId($trainerId);
        $participantList = $participantRepository->getParticipantsByTrainerId($trainerId);
        return [
            'invitationList' => $invitationList,
            'participantList' => $participantList,
        ];
    }

    
}


?>