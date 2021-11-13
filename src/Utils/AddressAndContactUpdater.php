<?php

namespace App\Utils;

use Exception;
use Symfony\Component\Form\Form;
use App\Entity\Invitation\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AddressAndContactUpdater{
    private EntityManagerInterface $entityManager;
    private Request $request;

    private string $result = 'none';

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
    $this->entityManager = $entityManager;
    $this->request =  $requestStack->getCurrentRequest();
    }

    public function updateAddress(Form $form)
    {
        $form->handleRequest($this->request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $address = $form->getData();
            try {
                $this->entityManager->persist($address);
                $this->entityManager->flush();
                $this->result = 'success';
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

}
?>