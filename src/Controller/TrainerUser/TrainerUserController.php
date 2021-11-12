<?php
namespace App\Controller\TrainerUser;

use App\Entity\Security\User;
use App\Repository\InvitationRepository;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
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

    /**
     * @Route("/show_all_plans_for_user/{user}", name="show_all_plan_for_user")
     * @Template()
     */
    public function showAllPlansForUser(User $user, Security $security, ParticipantRepository $participantRepository)
    {
        $trainerId = $security->getUser()->getId();
        $userId = $user->getId();
        $participantsWithPlans = $participantRepository->getParticipantWithAccessPlan($trainerId,$userId);

        return [
            'participant' => $user,
            'participantsWithPlans' => $participantsWithPlans
        ];
    }

    /**
     * @Route("/show_all_plans_for_participants", name="show_all_plan_for_participants")
     * @Template()
     */
    public function showAllPlansForParticipants(User $user, Security $security, ParticipantRepository $participantRepository)
    {
        $trainerId = $security->getUser()->getId();
        $participantsWithPlans = $participantRepository->getParticipantsWithAccessPlan($trainerId);

        return [
            'participantsWithPlans' => $participantsWithPlans
        ];
    }
}


?>