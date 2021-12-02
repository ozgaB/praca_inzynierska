<?php

namespace App\Utils;

use Exception;
use App\Entity\Security\User;
use App\Entity\Payment\Payment;
use Symfony\Component\Form\Form;
use App\Entity\Invitation\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Subscription\SubscriptionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PaymentInsertToDB
{
    private EntityManagerInterface $entityManager;
    private $request;

    private string $result = 'none';

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->request =  $requestStack->getCurrentRequest();
    }

    public function insertPaymentToDB(User $user, SubscriptionType $subscriptionType): Payment
    {
        $payment = (new Payment())
        ->setStatus("Oczekiwanie na płatność")
        ->setIdUser($user->getId())
        ->setIdSubscriptionType($subscriptionType->getId());
        try {
            $this->entityManager->persist($payment);
            $this->entityManager->flush();
            return $payment;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
