<?php
namespace App\Controller\Admin;

use App\Utils\CRUD\UserCRUD;
use App\Entity\Security\User;
use App\Utils\UserBioUpdater;
use App\Form\Type\UserBioType;
use App\Entity\UserBio\UserBio;
use App\Form\Type\UserDataType;
use App\Utils\TrainerBioUpdater;
use App\Form\Type\TrainerBioType;
use App\Form\Type\UserFilterType;
use App\Utils\PageElementUpdater;
use App\Form\Type\PageElementType;
use App\Repository\UserRepository;
use App\Utils\SubscriptionManager;
use App\Form\Type\RegisterUserType;
use App\Form\Type\UserPasswordType;
use App\Utils\Filtrs\FilterManager;
use App\Utils\InvitationUserSender;
use App\Entity\TrainerBio\TrainerBio;
use App\Form\Type\UserRoleFilterType;
use App\Repository\UserBioRepository;
use App\Utils\AccountSecurityUpdater;
use App\Repository\MainPageRepository;
use App\Utils\AddressAndContactUpdater;
use App\Entity\MainPage\MainPageElement;
use App\Form\Type\AddressAndContactType;
use App\Repository\InvitationRepository;
use App\Repository\TrainerBioRepository;
use App\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\AddressAndContactRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AddressAndContact\AddressAndContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AdminController
 */
class AdminController extends AbstractController
{
    /**
     *@Route("/admin_home_page", name="admin_home_page")
     *@IsGranted("ROLE_ADMIN")
     * @Template()
     */
    public function index(Security $security)
    {
        $user = $security->getUser();


        return [
        'user' => $user,
    ];
    }

    /**
     *@Route("/account_management", name="account_management")
     *@IsGranted("ROLE_ADMIN")
     * @Template()
     */
    public function accountManagement(Security $security, Request $request, UserRepository $userRepository, FilterManager $filterManager)
    {
        $currentUser = $security->getUser();
        $filterForm = $this->createForm(UserFilterType::class);
        if($filterManager->getFilterForm($filterForm)){
            $users =  $filterManager->getUserFilter($filterForm,$userRepository->getAllUserActiveQB())->getQuery()->getResult();
        } else {
            $users = $userRepository->getAllUserActiveQB()->getQuery()->getResult();
        }

        return [
        'currentUser' => $currentUser,
        'users' => $users,
        'filterForm' => $filterForm->createView(),
    ];
    }

    /**
     *@Route("/account_accept", name="account_accept")
     *@IsGranted("ROLE_ADMIN")
     * @Template()
     */
    public function accountAccept(Security $security, Request $request, UserRepository $userRepository, FilterManager $filterManager)
    {
        $currentUser = $security->getUser();
        $filterForm = $this->createForm(UserFilterType::class);
        if($filterManager->getFilterForm($filterForm)){
            $users =  $filterManager->getUserFilter($filterForm,$userRepository->getAllUserInActiveQB())->getQuery()->getResult();
        } else {
            $users = $userRepository->getAllUserInActiveQB()->getQuery()->getResult();
        }

        return [
        'usersInActive' => $users,
        'filterForm' => $filterForm->createView(),
    ];
    }

    /**
     *@Route("/subscription_management", name="subscription_management")
     *@IsGranted("ROLE_ADMIN")
     * @Template()
     */
    public function subscriptionManagement(Security $security, Request $request, UserRepository $userRepository, FilterManager $filterManager)
    {
        $user = $security->getUser();
        $currentUser = $security->getUser();
        $filterForm = $this->createForm(UserFilterType::class);
        if($filterManager->getFilterForm($filterForm)){
            $users =  $filterManager->getUserFilter($filterForm)->getQuery()->getResult();
        } else {
            $users = $userRepository->getAllUsersQB()->getQuery()->getResult();
        }

        return [
        'user' => $user,
        'usersInActive' => $users,
        'filterForm' => $filterForm->createView(),
    ];
    }

    /**
     *@Route("/main_site_management", name="main_site_management")
     *@IsGranted("ROLE_ADMIN")
     * @Template()
     */
    public function mainSiteManagement(MainPageRepository $pageRepository, PageElementUpdater $pageElementUpdater)
    {
        $pageElement = $pageRepository->getAllFromPageElement();
        if(null !== $pageElement)
        {
            $formPageElement = $this->createForm(PageElementType::class, $pageElement);
        } else {
            $pageElement = (new MainPageElement());
            $formPageElement = $this->createForm(PageElementType::class, $pageElement);
        }
        if('success' === $pageElementUpdater->updatePageElement($formPageElement))
        {
            $this->addFlash('success', 'Brawo dane zostały pomyślnie zmienione!');
        }

        return [
            'formPageElement' => $formPageElement->createView(),
    ];
    }

    /**
     *@Route("/activate_account/{user}", name="activate_account" , requirements={"user"="\d+"})
     *@IsGranted("ROLE_ADMIN")
     **/
    public function activateAccount(User $user,UserCRUD $userCRUD,SubscriptionManager $subscriptionManager)
    {
        if($userCRUD->setIsActiveToTrue($user)){
            $this->addFlash('success', 'Użytkownik '.$user->getFullName().' został aktywowany!');
            if(in_array('ROLE_TRAINER',$user->getRoles()))
            {
                $subscriptionManager->giveTrialSubscription($user);
            }
        }
        else{
            $this->addFlash('danger', 'Nie udało się aktywować tego użytkownika !');
        }
        return $this->redirectToRoute('account_accept');
    }

    /**
     *@Route("{returnRoute}/remove_account/{user}", name="remove_account")
     *@IsGranted("ROLE_ADMIN")
     **/
    public function removeAccount(User $user,UserCRUD $userCRUD,string $returnRoute)
    {
        if($userCRUD->removeAccount($user)){
            $this->addFlash('success', 'Użytkownik '.$user->getFullName().' został usunięty!');
        }
        else{
            $this->addFlash('danger', 'Nie udało się usunąć tego użytkownika !');
        }

        if('account_management' === $returnRoute){
            return $this->redirectToRoute('account_management');
        }
        return $this->redirectToRoute('account_accept');
    }

    /**
     *@Route("/show_and_eddit_account/{user}", name="show_and_edit_account")
     *@IsGranted("ROLE_ADMIN")
     */
    public function showAndEditAccountByRole(Security $security, Request $request, UserRepository $userRepository, FilterManager $filterManager,User $user)
    {
        if (in_array("ROLE_TRAINER", $user->getRoles())) {
            return $this->redirectToRoute("show_and_edit_trainer_user",['user' => $user->getId()]);
        } else {
            return $this->redirectToRoute("show_and_edit_standard_user",['user' => $user->getId()]);
        }
    }

    /**
     *@Route("/show_and_edit_standard_user/{user}", name="show_and_edit_standard_user")
     *@IsGranted("ROLE_ADMIN")
     * @Template()
     */
    public function showAndEditStandardUser(User $user,AddressAndContactUpdater $addressUpdater, UserBioUpdater $userBioUpdater, AddressAndContactRepository $addressRepository, UserBioRepository $userBioRepository)
    {
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
            'formUserBio' => $formUserBio->createView(),
            'formAddress' => $formAddress->createView(),
            'user' => $user,
            'userBio' => $userBio,
            'address' => $address,
        ];
    }

    /**
     *@Route("/show_and_edit_trainer_user/{user}", name="show_and_edit_trainer_user")
     *@IsGranted("ROLE_ADMIN")
     * @Template()
     */
    public function showAndEditTrainerUser(User $user, AddressAndContactUpdater $addressUpdater, TrainerBioUpdater $trainerBioUpdater, AddressAndContactRepository $addressRepository, TrainerBioRepository $trainerBioRepository)
    {
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
            'formTrainerBio' => $formTrainerBio->createView(),
            'formAddress' => $formAddress->createView(),
            'user' => $user,
            'trainerBio' => $trainerBio,
            'address' => $address,
        ];
    }

}
