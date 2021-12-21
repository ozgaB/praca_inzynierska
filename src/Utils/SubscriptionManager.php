<?php

namespace App\Utils;

use DateTime;
use Exception;
use App\Entity\Security\User;
use Symfony\Component\Form\Form;
use App\Entity\Invitation\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Subscription\Subscription;
use App\Entity\Subscription\SubscriptionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\SubscriptionRepository;

class SubscriptionManager
{
    const THREE_DAYS_IN_SECONDS = 259200;

    private EntityManagerInterface $entityManager;
    private $request;
    private SubscriptionRepository $subscriptionRepository;

    private string $result = 'none';

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, SubscriptionRepository $subscriptionRepository)
    {
        $this->entityManager = $entityManager;
        $this->request =  $requestStack->getCurrentRequest();
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function createOrUpdateSubscription(User $user, int $lifeTime)
    {
        if (null !== $subscription = $this->subscriptionRepository->findOneBy(['idTrainer' => $user->getId()])) {
            $subscription->setCreatedAt(new DateTime("now"));
            $subscription->setExpireAt($this->getExpireAtDateTime($lifeTime));
            try {
                $this->entityManager->persist($subscription);
                $this->entityManager->flush();
                $this->result = 'success';
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            $subscription = new Subscription();
            $subscription->setIdTrainer($user->getId());
            $subscription->setCreatedAt(new DateTime("now"));
            $subscription->setExpireAt($this->getExpireAtDateTime($lifeTime));
            try {
                $this->entityManager->persist($subscription);
                $this->entityManager->flush();
                $this->result = 'success';
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        return $this->result;
    }

    public function getExpireAtDateTime(int $lifeTime)
    {
        $dateTime = new DateTime("now");
        $dateTime->modify("+{$lifeTime} seconds");
        return $dateTime;
    }

    public function giveTrialSubscription(User $user)
    {
        $subscription = (new Subscription())
        ->setIdTrainer($user->getId())
        ->setCreatedAt(new DateTime("now"))
        ->setExpireAt($this->getExpireAtDateTime(10));
        try {
            $this->entityManager->persist($subscription);
            $this->entityManager->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function checkSubscriptionIsValid(User $user): bool
    {
        if (null !== $subscription = $this->subscriptionRepository->findOneBy(['idTrainer' => $user->getId()])) {
            if (new DateTime("now") < $subscription->getExpireAt()) {
                return true;
            }
        }
        return false;
    }


    public function addOrUpdateSubscription(Form $form)
    {
        $form->handleRequest($this->request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $subscription = $form->getData();
            try {
                $this->entityManager->persist($subscription);
                $this->entityManager->flush();
                $this->result = 'success';
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        return $this->result;
    }

    public function removeSubscription(Subscription $subscription)
    {
        try {
            $this->entityManager->remove($subscription);
            $this->entityManager->flush();
            $this->result = 'success';
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return $this->result;
    }

    public function isSubscriptionExpireIn3Days(User $user)
    {
        if (null !== $subscription = $this->subscriptionRepository->findOneBy(['idTrainer' => $user->getId()])) {
            $modifytDate = (new DateTime("now"))->modify("+ 3 days");
            if ((new DateTime("now") < $subscription->getExpireAt())&&($modifytDate > $subscription->getExpireAt())) {
                return true;
            }
        }
        return false;
    }

    public function updateSubscriptionExpireAt(User $user,int $lifeTime)
    {
        if (null !== $subscription = $this->subscriptionRepository->findOneBy(['idTrainer' => $user->getId()])) {
            $expireAt = $subscription->getExpireAt();
            $subscription->setExpireAt($this->updateExpireAtDateTimeAddingLifeTime($lifeTime,$expireAt));
            try {
                $this->entityManager->persist($subscription);
                $this->entityManager->flush();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            $subscription = new Subscription();
            $subscription->setIdTrainer($user->getId());
            $subscription->setCreatedAt(new DateTime("now"));
            $subscription->setExpireAt($this->getExpireAtDateTime($lifeTime));
            try {
                $this->entityManager->persist($subscription);
                $this->entityManager->flush();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

    public function updateExpireAtDateTimeAddingLifeTime(int $lifeTime, DateTime $expireAt)
    {
        $expireAt->modify("+{$lifeTime} seconds");

        return $expireAt;
    }
}
