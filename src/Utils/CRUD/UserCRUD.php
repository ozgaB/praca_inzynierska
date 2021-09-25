<?php

namespace App\Utils\CRUD;

use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCRUD{

private EntityManagerInterface $entityManager;
private UserPasswordEncoderInterface $passwordEncoder;

public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
{
    $this->entityManager = $entityManager;
    $this->passwordEncoder = $passwordEncoder;
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
        } 
        else{
          $user->setRoles(['ROLE_TRAINER']);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return true;
    }

  }  


}
?>