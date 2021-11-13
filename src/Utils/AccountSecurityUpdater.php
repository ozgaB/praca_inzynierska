<?php

namespace App\Utils;

use Exception;
use App\Entity\Security\User;
use Symfony\Component\Form\Form;
use App\Repository\UserRepository;
use App\Entity\Invitation\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountSecurityUpdater{
    private UserPasswordEncoderInterface $passwordEncoder;
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private $request;

    private string $result = 'none';

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack,UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
    $this->entityManager = $entityManager;
    $this->request =  $requestStack->getCurrentRequest();
    $this->passwordEncoder = $passwordEncoder;
    $this->userRepository = $userRepository;
    }

    public function updateAccount(Form $formAccountData)
    {
        $formAccountData->handleRequest($this->request);
        
        if ($formAccountData->isSubmitted() && $formAccountData->isValid()) { 
            $user = $formAccountData->getData();
            try {
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->result = 'success';
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

    public function updatePassword(Form $formPassword,User $user)
    {
    $formPassword->handleRequest($this->request);
        if ($formPassword->isSubmitted() && $formPassword->isValid()) { 
            $userData = $formPassword->getData();
            $oldPassword = $formPassword->get('oldPassword')->getData();
            if($this->passwordEncoder->isPasswordValid($user,$oldPassword))
            {
                try {
                    $this->entityManager->persist($userData);
                    $this->entityManager->flush();
                    $this->result = 'success';
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
            }
            $this->result = 'invalidOldPassword';
        }
    }
}
?>