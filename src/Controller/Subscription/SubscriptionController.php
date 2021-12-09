<?php
namespace App\Controller\Subscription;

use DateTime;
use App\Entity\Security\User;
use App\Repository\UserRepository;
use App\Utils\SubscriptionManager;
use App\Form\Type\RegisterUserType;
use App\Repository\MainPageRepository;
use App\Entity\Subscription\Subscription;
use App\Repository\SubscriptionRepository;
use App\Entity\Subscription\SubscriptionType;
use App\Form\Type\SubscriptionEditExpireType;
use App\Form\Type\SubscriptionForTrainerType;
use App\Repository\SubscriptionTypeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security as SecurityUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class SubscriptionController
 */
class SubscriptionController extends AbstractController
{
    /**
     *@Route("/subscription_list", name="subscription_list")
     *@Template()
     *@IsGranted("ROLE_ADMIN")
     */
    public function subscriptionList(SubscriptionRepository $subscriptionRepository,UserRepository $userRepository)
    {
        //$activeSubscribers = $subscriptionRepository->getAllSubscribers();
        $currentDate = new DateTime("now");
        $subscribersList = $userRepository->getTrainersSubscriptionStatus();
        return [
            'currentDate' => $currentDate,
            'subscribersList' => $subscribersList,
    ];
    }

    /**
     *@Route("/edit_subscription/{subscription}", name="edit_subscription", requirements={"subscription"="\d+"})
     *@IsGranted("ROLE_ADMIN")
     *@Template()
     */
    public function editSubscription(Subscription $subscription,SubscriptionManager $subscriptionManager)
    {
        $form = $this->createForm(SubscriptionEditExpireType::class, $subscription);
        if('success' === $subscriptionManager->addOrUpdateSubscription($form))
        {
            $this->addFlash('success', 'Brawo subskrypcja została edytowana!');
            return $this->redirectToRoute('subscription_list');
        }
        return [
            'form' => $form->createView(),
    ]; 
    }

    /**
     *@Route("/add_subscription/{user}", name="add_subscription", requirements={"user"="\d+"})
     *@IsGranted("ROLE_ADMIN")
     *@Template()
     */
    public function addSubscription(User $user, SubscriptionManager $subscriptionManager)
    {
        $subscription = (new Subscription())
        ->setIdTrainer($user->getId());
        $form = $this->createForm(SubscriptionForTrainerType::class, $subscription);
        if('success' === $subscriptionManager->addOrUpdateSubscription($form))
        {
            $this->addFlash('success', 'Brawo subskrypcja została dodana!');
            return $this->redirectToRoute('subscription_list');
        }
        return [
            'form' => $form->createView(),
            'fullName' => $user->getFullName(),
    ]; 
    }

    /**
     *@Route("/remove_subscription/{subscription}", name="remove_subscription", requirements={"subscription"="\d+"})
     *@IsGranted("ROLE_ADMIN")
     */
    public function removeSubscription(Subscription $subscription,SubscriptionManager $subscriptionManager)
    {
        if('success' === $subscriptionManager->removeSubscription($subscription))
        {
            $this->addFlash('success', 'Brawo subskrypcja została pomyślnie usunięta!');
            return $this->redirectToRoute('subscription_list');
        }
        return [

    ];
    }

    /**
     *@Route("/add_update_subscription_action/{user}/{subscriptionType}", name="add_update_subscription_action")
     */
    public function addOrUpdateSubscriptionAction(User $user,SubscriptionType $subscriptionType, SubscriptionManager $subscriptionManager)
    {
       $lifeTime = $subscriptionType->getLifeTimeInSeconds();
            if('success' === $subscriptionManager->createOrUpdateSubscription($user,$lifeTime))
            {
                $this->addFlash('success', 'Brawo twoja subskrypcja zostrała aktywowana!');
                return $this->redirectToRoute('manage_subscription_trainer');
            }
        return $this->redirectToRoute('manage_subscription_trainer');
    }

    /**
     *@Route("/manage_subscription_trainer", name="manage_subscription_trainer")
     *@IsGranted("ROLE_TRAINER")
     *@Template()
     */
    public function manageSubscriptionTrainer(SecurityUser $security, SubscriptionTypeRepository $subscriptionTypeRepository, SubscriptionRepository $subscriptionRepository)
    {
        $currentTrainer =  $security->getUser();
        $subscriptionTypeList = $subscriptionTypeRepository->getSubscriptionTypeList();
        $subscription = $subscriptionRepository->findOneBy(['idTrainer' => $currentTrainer]);

        return [
            'currentTrainer' => $currentTrainer,
            'subscriptionTypeList' => $subscriptionTypeList,
            'subscription' => $subscription,
    ];
    }
}
