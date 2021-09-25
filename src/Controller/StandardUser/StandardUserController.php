<?php
namespace App\Controller\StandardUser;

use App\Entity\Security\User;
use App\Repository\UserRepository;
use App\Form\Type\RegisterUserType;
use App\Utils\InvitationUserSender;
use App\Repository\InvitationRepository;
use App\Repository\ParticipantRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class StandardUserController
 */
Class StandardUserController extends AbstractController
{
/**
 *@Route("/user_home_page", name="user_home_page")
 *@Template()
 */
public function index()
{
    return [

    ];
}

/**
 * @Route("/user_trainers", name="user_trainers")
 * 
 * @Template()
 */
public function userTrainers(Security $security,UserRepository $userRepository, ParticipantRepository $participantRepository, InvitationRepository $invitationRepository)
{
    $user = $security->getUser();
    $trainers = $userRepository->getUsersWithRoleTrainerAndCheckInvitation();
    $actualTrainer = $participantRepository->getTrainerWithDataByUserId($user->getId());

    return [
        'actualTrainer' => $actualTrainer,
        'user' => $user,
        'trainers' => $trainers
    ];
}

/**
 * @Route("/send_invitation/{user}", name="send_invitation")
 */
public function sendInvitationByUser(User $user,Security $security,InvitationUserSender $inviatationUserSender)
{
    $currentUser = $security->getUser();
    if($inviatationUserSender->sendInvitation($currentUser,$user)){
        $this->addFlash('success', 'Trener zostanie powiadomiony o twoim zaproszeniu!');
    }
    else{
        $this->addFlash('danger', 'Nie udało się wysłać zaproszenia !');
    }
    return $this->redirectToRoute('user_trainers');
}
}
?>