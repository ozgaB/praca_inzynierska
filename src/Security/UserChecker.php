<?php
namespace App\Security;

use App\Repository\UserRepository;
use App\Utils\SubscriptionManager;
use App\Entity\Security\User as AppUser;
use App\Repository\SubscriptionRepository;
use App\Exception\RedirectToBuyAccessException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{

    private UserRepository $userRepository;
    private SubscriptionManager $subscriptionManager;

    public function __construct(UserRepository $userRepository, SubscriptionManager $subscriptionManager)
    {
        $this->userRepository = $userRepository;
        $this->subscriptionManager = $subscriptionManager;
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (false === $user->getIsActive()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Twoje konto nie zostało jeszcze akceptowane');
        }
        if (in_array('ROLE_TRAINER',$user->getRoles()) && ((empty($this->userRepository->getTrainerSubscription($user->getId())) || !($this->subscriptionManager->checkSubscriptionIsValid($user)))))
        {
            throw new CustomUserMessageAccountStatusException('Twoja subskrybcja wygasła, skontaktuj się z administratorem lub odwiedź nasz sklep by ją odnowić');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }
}
?>