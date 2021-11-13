<?php
namespace App\Controller\TrainerUser;

use App\Entity\Security\User;
use App\Form\Type\UserDataType;
use App\Utils\TrainerBioUpdater;
use App\Form\Type\TrainerBioType;
use App\Repository\UserRepository;
use App\Form\Type\UserPasswordType;
use App\Entity\TrainerBio\TrainerBio;
use App\Utils\AccountSecurityUpdater;
use App\Utils\AddressAndContactUpdater;
use App\Form\Type\AddressAndContactType;
use App\Repository\InvitationRepository;
use App\Repository\TrainerBioRepository;
use App\Repository\ParticipantRepository;
use Symfony\Component\Security\Core\Security;
use App\Repository\AddressAndContactRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AddressAndContact\AddressAndContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class TrainerUserController extends AbstractController
{
    /**
    *@Route("/trainer_home_page", name="trainer_home_page")
    * @Template()
    * @IsGranted("ROLE_TRAINER")
    */
    public function index(Security $security, AccountSecurityUpdater $accUpdater, AddressAndContactUpdater $addressUpdater, TrainerBioUpdater $trainerBioUpdater, AddressAndContactRepository $addressRepository, TrainerBioRepository $trainerBioRepository)
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
    
        $trainerBio = $trainerBioRepository->findOneBy(['idUser' => $user->getId()]);
        if(null !== $trainerBio)
        {
            $formTrainerBio = $this->createForm(TrainerBioType::class, $trainerBio);
        } else {
            $trainerBio = (new TrainerBio())
            ->setIdUser($user->getId());
            $formTrainerBio = $this->createForm(TrainerBioType::class, $trainerBio);
        }
        if('success' === $trainerBioUpdater->updateBio($formTrainerBio))
        {
            $this->addFlash('success', 'Brawo dane zostały pomyślnie zmienione!');
        }
    
        return [
            'formUserData' => $formAccountData->createView(),
            'formPassword' => $formPassword->createView(),
            'formTrainerBio' => $formTrainerBio->createView(),
            'formAddress' => $formAddress->createView(),
            'user' => $user,
            'trainerBio' => $trainerBio,
            'address' => $address,
        ];
    }

    /**
     * @Route("/trainer_users", name="trainer_users")
     * @Template()
     * @IsGranted("ROLE_TRAINER")
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
     * @IsGranted("ROLE_TRAINER")
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
     * @IsGranted("ROLE_TRAINER")
     */
    public function showAllPlansForParticipants(Security $security, ParticipantRepository $participantRepository)
    {
        $trainerId = $security->getUser()->getId();
        $participantsWithPlans = $participantRepository->getParticipantsWithAccessPlan($trainerId);

        return [
            'participantsWithPlans' => $participantsWithPlans
        ];
    }
}


?>