<?php

namespace App\Utils\CRUD;

use App\Entity\Security\User;
use Symfony\Component\Form\Form;
use TheSeer\Tokenizer\Exception;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCRUD{

private EntityManagerInterface $entityManager;
private UserRepository $userRepository;
private UserPasswordEncoderInterface $passwordEncoder;

public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
{
    $this->entityManager = $entityManager;
    $this->passwordEncoder = $passwordEncoder;
    $this->userRepository = $userRepository;
}

  public function registerUser($form)
  {
    if($form->isSubmitted() && $form->isValid())
    {
        $user = $form->getData();
        $newPass = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($newPass);
        $role = $form->get('role')->getData();
        if(false === $role){
          $user->setRoles(['ROLE_USER']);
          $user->setIsActive(true);
        } 
        else{
          $user->setRoles(['ROLE_TRAINER']);
          $user->setIsActive(false);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return true;
    }

  }  

  public function setIsActiveToTrue(User $user)
  {
    try{
      $user->setIsActive(true);
      $this->entityManager->persist($user);
      $this->entityManager->flush();
      return true;
    }
    catch (Exception $e)
    {
      throw new Exception($e->getMessage());
    }
  }

  public function removeAccount(User $user)
  {
    try{
      $this->entityManager->remove($user);
      $this->entityManager->flush();
      return true;
    }
    catch (Exception $e)
    {
      throw new Exception($e->getMessage());
    }
  }
}
?>