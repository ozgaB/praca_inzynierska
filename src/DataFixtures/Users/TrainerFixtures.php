<?php

namespace App\DataFixtures\Users;

use App\Entity\Security\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TrainerFixtures extends Fixture
{
    const TRAINER_1_REFERENCE = 'trainer-1-reference';
    const TRAINER_1_FIRST_NAME = 'Trener';
    const TRAINER_1_LAST_NAME = 'Testowy';
    const TRAINER_1_EMAIL = 'trenertestowy@trener.pl';
    const TRAINER_1_PASSWORD = 'trener123';

    const TRAINER_2_REFERENCE = 'trainer-2-reference';
    const TRAINER_2_FIRST_NAME = 'Trener';
    const TRAINER_2_LAST_NAME = '123';
    const TRAINER_2_EMAIL = 'trener123@trener.pl';
    const TRAINER_2_PASSWORD = 'trener123';

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach($this->getTrainerData() as $trainer)
        {
            $user = new User();
            $user
            ->setFirstName($trainer['firstName'])
            ->setLastName($trainer['lastName'])
            ->setEmail($trainer['email'])
            ->setPassword($this->passwordEncoder->encodePassword($user, $trainer['password']))
            ->setRoles(['ROLE_TRAINER'])
            ->setIsActive(false);
            $manager->persist($user);
            $this->addReference($trainer['reference'], $user);
        }

        $manager->flush();
    }

    public function getTrainerData(): array
    {
        return [
            'Admin_1' => [
                'firstName' => self::TRAINER_1_FIRST_NAME,
                'lastName' => self::TRAINER_1_LAST_NAME,
                'email' => self::TRAINER_1_EMAIL,
                'password' => self::TRAINER_1_PASSWORD,
                'reference' => self::TRAINER_1_REFERENCE,
            ],
            'Admin_2' => [
                'firstName' => self::TRAINER_2_FIRST_NAME,
                'lastName' => self::TRAINER_2_LAST_NAME,
                'email' => self::TRAINER_2_EMAIL,
                'password' => self::TRAINER_2_PASSWORD,
                'reference' => self::TRAINER_2_REFERENCE,
            ],
        ];
    }
}