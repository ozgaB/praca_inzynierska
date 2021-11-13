<?php

namespace App\Utils\Filtrs;

use Exception;
use App\Entity\Security\User;
use Symfony\Component\Form\Form;
use App\Repository\UserRepository;
use App\Entity\Invitation\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FilterManager{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private $request;

    private string $result = 'none';

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, UserRepository $userRepository)
    {
    $this->entityManager = $entityManager;
    $this->request =  $requestStack->getCurrentRequest();
    $this->userRepository = $userRepository;
    }

    public function getUserFilter(Form $form)
    {
        $role = $form->get('userRole')->getData();
        $firstName = $form->get('firstName')->getData();
        $lastName = $form->get('lastName')->getData();
        $email = $form->get('email')->getData();

        $qb = $this->userRepository->getAllUsersQB();
        if(isset($role)){
            $qb = $this->userRepository->getUserRoleFilter($qb,$role);
        }
        if(isset($firstName)){
            $qb = $this->userRepository->getUserFirstNameFilter($qb,$firstName);
        }
        if(isset($lastName)){
            $qb = $this->userRepository->getUserLastNameFilter($qb,$lastName);
        }
        if(isset($email)){
            $qb = $this->userRepository->getUserEmailFilter($qb,$email);
        }

        return $qb;
    }

    public function getFilterForm(Form $form)
    {
        $form->handleRequest($this->request);
        if($form->getClickedButton() === $form->get('save'))
        {
            return true;
        } elseif ($form->getClickedButton() === $form->get('reset')) {
            return false;
        } else {
            return false;
        }
    }

}
?>