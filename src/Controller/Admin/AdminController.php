<?php
namespace App\Controller\Admin;

use App\Entity\Security\User;
use App\Utils\UserBioUpdater;
use App\Form\Type\UserBioType;
use App\Entity\UserBio\UserBio;
use App\Form\Type\UserDataType;
use App\Form\Type\UserFilterType;
use App\Repository\UserRepository;
use App\Form\Type\RegisterUserType;
use App\Form\Type\UserPasswordType;
use App\Utils\Filtrs\FilterManager;
use App\Utils\InvitationUserSender;
use App\Form\Type\UserRoleFilterType;
use App\Repository\UserBioRepository;
use App\Utils\AccountSecurityUpdater;
use App\Utils\AddressAndContactUpdater;
use App\Form\Type\AddressAndContactType;
use App\Repository\InvitationRepository;
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
            $users =  $filterManager->getUserFilter($filterForm)->getQuery()->getResult();
        } else {
            $users = $userRepository->getAllUsersQB()->getQuery()->getResult();
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
    public function accountAccept(Security $security)
    {
        $user = $security->getUser();


        return [
        'user' => $user,
    ];
    }
}
