<?php
namespace App\Controller\BuyAccess;

use App\Entity\Security\User;
use App\Form\Type\RegisterUserType;
use App\Repository\MainPageRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\SubscriptionTypeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security as SecurityUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Subscription\SubscriptionType;
use App\Utils\SubscriptionManager;
use App\Repository\UserRepository;

/**
 * Class SubscriptionController
 */
class BuyAccessController extends AbstractController
{
    /**
     *@Route("/buy_access/{user}", name="buy_access")
     *@Template()
     */
    public function buyAccess(User $user,SubscriptionTypeRepository $subscriptionTypeRepository)
    {
        if (!in_array("ROLE_TRAINER", $user->getRoles())) {
            return $this->redirectToRoute("home_page");
        }
        $subscriptionTypeList = $subscriptionTypeRepository->getSubscriptionTypeList();
        $currentTrainer = $user->getId();

        return [
            'subscriptionTypeList' => $subscriptionTypeList,
            'currentTrainer' => $currentTrainer,
    ];
    }
}
?>