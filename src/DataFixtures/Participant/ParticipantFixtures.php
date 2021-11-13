<?php

namespace App\DataFixtures\Participant;

use App\Entity\Security\User;
use App\Entity\Participant\Participant;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Users\AdminFixtures;
use App\DataFixtures\Users\TrainerFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Users\StandardUserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantFixtures extends Fixture implements DependentFixtureInterface
{
    const PARTICIPANT_1_REFERENCE = 'participant-1-reference';
    const PARTICIPANT_2_REFERENCE = 'participant-2-reference';
    const PARTICIPANT_3_REFERENCE = 'participant-3-reference';
    const PARTICIPANT_4_REFERENCE = 'participant-4-reference';

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach($this->getTrainerData() as $participant)
        {
            $user = new Participant();
            $user
            ->setIdUser($this->getReference($participant['user'])->getId())
            ->setIdTrainer($this->getReference($participant['trainer'])->getId());
            $manager->persist($user);
            $this->addReference($participant['reference'], $user);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AdminFixtures::class,
            StandardUserFixtures::class,
            TrainerFixtures::class,
        ];
    }

    public function getTrainerData(): array
    {
        return [
            'Participant_1' => [
                'user' => StandardUserFixtures::USER_1_REFERENCE,
                'trainer' => TrainerFixtures::TRAINER_1_REFERENCE,
                'reference' => self::PARTICIPANT_1_REFERENCE,
            ],
            'Participant_2' => [
                'user' => StandardUserFixtures::USER_2_REFERENCE,
                'trainer' => TrainerFixtures::TRAINER_1_REFERENCE,
                'reference' => self::PARTICIPANT_2_REFERENCE,
            ],
            'Participant_3' => [
                'user' => StandardUserFixtures::USER_1_REFERENCE,
                'trainer' => TrainerFixtures::TRAINER_2_REFERENCE,
                'reference' => self::PARTICIPANT_3_REFERENCE,
            ],
            'Participant_4' => [
                'user' => StandardUserFixtures::USER_2_REFERENCE,
                'trainer' => TrainerFixtures::TRAINER_2_REFERENCE,
                'reference' => self::PARTICIPANT_4_REFERENCE,
            ],
        ];
    }
}