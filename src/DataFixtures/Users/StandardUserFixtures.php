<?php

namespace App\DataFixtures\Users;

use App\Entity\Security\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StandardUserFixtures extends Fixture
{
    const USER_1_REFERENCE = 'user-1-reference';
    const USER_1_FIRST_NAME = 'user';
    const USER_1_LAST_NAME = 'testowy';
    const USER_1_EMAIL = 'usertestowy123@user.pl';
    const USER_1_PASSWORD = 'user123';

    const USER_2_REFERENCE = 'user-2-reference';
    const USER_2_FIRST_NAME = 'user';
    const USER_2_LAST_NAME = '123';
    const USER_2_EMAIL = 'user123@user.pl';
    const USER_2_PASSWORD = 'user123';

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach($this->getStandardUserData() as $standardUser)
        {
            $user = new User();
            $user
            ->setFirstName($standardUser['firstName'])
            ->setLastName($standardUser['lastName'])
            ->setEmail($standardUser['email'])
            ->setPassword($this->passwordEncoder->encodePassword($user, $standardUser['password']))
            ->setRoles(['ROLE_USER'])
            ->setIsActive(true);
            $manager->persist($user);
            $this->addReference($standardUser['reference'], $user);
        }

        $manager->flush();
    }

    public function getStandardUserData(): array
    {
        return [
            'User_1' => [
                'firstName' => self::USER_1_FIRST_NAME,
                'lastName' => self::USER_1_LAST_NAME,
                'email' => self::USER_1_EMAIL,
                'password' => self::USER_1_PASSWORD,
                'reference' => self::USER_1_REFERENCE,
            ],
            'User_2' => [
                'firstName' => self::USER_2_FIRST_NAME,
                'lastName' => self::USER_2_LAST_NAME,
                'email' => self::USER_2_EMAIL,
                'password' => self::USER_2_PASSWORD,
                'reference' => self::USER_2_REFERENCE,
            ],
        ];
    }
}