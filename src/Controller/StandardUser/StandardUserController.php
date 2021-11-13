<?php
namespace App\Controller\StandardUser;

use App\Entity\Security\User;
use App\Utils\UserBioUpdater;
use App\Form\Type\UserBioType;
use App\Entity\UserBio\UserBio;
use App\Form\Type\UserDataType;
use App\Repository\UserRepository;
use App\Form\Type\RegisterUserType;
use App\Form\Type\UserPasswordType;
use App\Utils\InvitationUserSender;
use App\Repository\UserBioRepository;
use App\Utils\AccountSecurityUpdater;
use App\Utils\AddressAndContactUpdater;
use App\Form\Type\AddressAndContactType;
use App\Repository\InvitationRepository;
use App\Repository\ParticipantRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\AddressAndContactRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AddressAndContact\AddressAndContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class StandardUserController
 */
Class StandardUserController extends AbstractController
{
/**
 *@Route("/user_home_page", name="user_home_page")
 *@IsGranted("ROLE_USER")
 * @Template()
 */
public function index(Security $security, AccountSecurityUpdater $accUpdater, AddressAndContactUpdater $addressUpdater, UserBioUpdater $userBioUpdater, AddressAndContactRepository $addressRepository, UserBioRepository $userBioRepository)
{
    $user = $security->getUser();
    $formAccountData = $this->createForm(UserDataType::class, $user);
    if('success' === $accUpdater->updateAccount($formAccountData))
    {
        $this->addFlash('success', 'Brawo dane zostały pomyślnie zmienione!');
    }

    $formPassword = $this->createForm(UserPasswordType::class, $user);
    $resultOfUpdatePassword = $accUpdater->updatePassword($formPassword,$user);
    if('success' === $resultOfUpdatePassword)
    {
        $this->addFlash('success', 'Brawo hasło zostało pomyślnie zmienione!');
    } elseif('InvaildOldPassword' === $resultOfUpdatePassword){
        $this->addFlash('danger', 'Stare hasło jest nieprawidłowe!');
    }

    $address = $addressRepository->findOneBy(['idUser' => $user->getId()]);
    if(null !== $address)
    {
        $formAddress = $this->createForm(AddressAndContactType::class, $address);
    } else {
        $address = (new AddressAndContact())
        ->setIdUser($user->getId());    
        $formAddress = $this->createForm(AddressAndContactType::class, $address);
    }
    if('success' === $addressUpdater->updateAddress($formAddress))
    {
        $this->addFlash('success', 'Brawo dane zostały pomyślnie zmienione!');
    }

    $userBio = $userBioRepository->findOneBy(['idUser' => $user->getId()]);
    if(null !== $userBio)
    {
        $formUserBio = $this->createForm(UserBioType::class, $userBio);
    } else {
        $userBio = (new UserBio())
        ->setIdUser($user->getId());
        $formUserBio = $this->createForm(UserBioType::class, $userBio);
    }
    if('success' === $userBioUpdater->updateBio($formUserBio))
    {
        $this->addFlash('success', 'Brawo dane zostały pomyślnie zmienione!');
    }

    return [
        'formUserData' => $formAccountData->createView(),
        'formPassword' => $formPassword->createView(),
        'formUserBio' => $formUserBio->createView(),
        'formAddress' => $formAddress->createView(),
        'user' => $user,
        'userBio' => $userBio,
        'address' => $address,
    ];
}

/**
 * @Route("/user_trainers", name="user_trainers")
 * @IsGranted("ROLE_USER")
 * @Template()
 */
public function userTrainers(Security $security,UserRepository $userRepository, ParticipantRepository $participantRepository, InvitationRepository $invitationRepository)
{
    $user = $security->getUser();
    $trainers = $userRepository->getUsersWithRoleTrainerAndCheckInvitation($user->getId());
    $actualTrainer = $participantRepository->getTrainerWithDataByUserId($user->getId());

    return [
        'actualTrainer' => $actualTrainer,
        'user' => $user,
        'trainers' => $trainers
    ];
}

/**
 * @Route("/send_invitation/{user}", name="send_invitation")
 * @IsGranted("ROLE_USER")
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

/**
 * @Route("/show_user_profile/{user}", name="show_user_profile")
 * @IsGranted("ROLE_TRAINER")
 * @Template()
 */
public function showUserProfile(User $user)
{


    return [
        'user' => $user,
    ];
}
}
?>