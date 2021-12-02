<?php

namespace App\Utils\CRUD;

use Exception;
use Symfony\Component\Form\Form;
use App\Entity\Invitation\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Subscription\SubscriptionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SubscriptionTypeCRUD{
    private EntityManagerInterface $entityManager;
    private $request;

    private string $result = 'none';

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
    $this->entityManager = $entityManager;
    $this->request =  $requestStack->getCurrentRequest();
    }

    public function addOrUpdateSubscriptionType(Form $form)
    {
        $form->handleRequest($this->request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $subscriptionType = $form->getData();
            try {
                $this->entityManager->persist($subscriptionType);
                $this->entityManager->flush();
                $this->result = 'success';
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        return $this->result;
    }

    public function removeSubscriptionType(SubscriptionType $subscriptionType)
    {
        try {
            $this->entityManager->remove($subscriptionType);
            $this->entityManager->flush();
            $this->result = 'success';
        } catch (Exception $e) {
            $this->result = 'faild';
        }

        return $this->result;
    }

}
?>