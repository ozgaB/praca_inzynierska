<?php

namespace App\DataFixtures\Users;

use App\Entity\Security\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture
{
    const ADMIN_1_REFERENCE = 'admin-1-reference';
    const ADMIN_1_FIRST_NAME = 'Administrator1';
    const ADMIN_1_LAST_NAME = 'Administrator1';
    const ADMIN_1_EMAIL = 'Administrator1@gmail.com';
    const ADMIN_1_PASSWORD = 'Nhytgb#';

    const ADMIN_2_REFERENCE = 'admin-2-reference';
    const ADMIN_2_FIRST_NAME = 'Administrator2';
    const ADMIN_2_LAST_NAME = 'Administrator2';
    const ADMIN_2_EMAIL = 'Administrator2@gmail.com';
    const ADMIN_2_PASSWORD = 'Kfhdjnskre58%#';

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach($this->getAdminData() as $admin)
        {
            $user = new User();
            $user
            ->setFirstName($admin['firstName'])
            ->setLastName($admin['lastName'])
            ->setEmail($admin['email'])
            ->setPassword($this->passwordEncoder->encodePassword($user, $admin['password']))
            ->setRoles(['ROLE_ADMIN'])
            ->setIsActive(true);
            $manager->persist($user);
            $this->addReference($admin['reference'], $user);
        }

        $manager->flush();
    }

    public function getAdminData(): array
    {
        return [
            'Admin_1' => [
                'firstName' => self::ADMIN_1_FIRST_NAME,
                'lastName' => self::ADMIN_1_LAST_NAME,
                'email' => self::ADMIN_1_EMAIL,
                'password' => self::ADMIN_1_PASSWORD,
                'reference' => self::ADMIN_1_REFERENCE,
            ],
            'Admin_2' => [
                'firstName' => self::ADMIN_2_FIRST_NAME,
                'lastName' => self::ADMIN_2_LAST_NAME,
                'email' => self::ADMIN_2_EMAIL,
                'password' => self::ADMIN_2_PASSWORD,
                'reference' => self::ADMIN_2_REFERENCE,
            ],
        ];
    }
}