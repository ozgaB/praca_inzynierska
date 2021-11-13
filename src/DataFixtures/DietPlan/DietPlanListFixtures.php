<?php
namespace App\DataFixtures\DietPlan;

use App\Entity\Security\User;
use App\Entity\Participant\Participant;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Users\AdminFixtures;
use App\DataFixtures\Users\TrainerFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Users\StandardUserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\DietPlan\DietPlanList;

class DietPlanListFixtures extends Fixture implements DependentFixtureInterface
{
    const DIET_PLAN_1_REFERENCE = 'diet-plan-1-reference';
    const DIET_PLAN_2_REFERENCE = 'diet-plan-2-reference';
    const DIET_PLAN_3_REFERENCE = 'diet-plan-3-reference';
    const DIET_PLAN_4_REFERENCE = 'diet-plan-4-reference';

    const DIET_PLAN_1_DESCRIPTION = 'przykładowy opis 1';
    const DIET_PLAN_2_DESCRIPTION = 'przykładowy opis 2';
    const DIET_PLAN_3_DESCRIPTION = 'przykładowy opis 3';
    const DIET_PLAN_4_DESCRIPTION = 'przykładowy opis 4';

    const DIET_PLAN_1_NAME = 'Plan testowy 1';
    const DIET_PLAN_2_NAME = 'Plan testowy 2';
    const DIET_PLAN_3_NAME = 'Plan testowy 3';
    const DIET_PLAN_4_NAME = 'Plan testowy 4';

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach($this->getTrainerData() as $data)
        {
            $dietPlan = new DietPlanList();
            $dietPlan
            ->setIdTrainer($this->getReference($data['trainer'])->getId())
            ->setDescription($data['description'])
            ->setPlanName($data['planName'])
            ->setCreatedAt();
            $manager->persist($dietPlan);
            $this->addReference($data['reference'], $dietPlan);
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
            'Plan_1' => [
                'trainer' => TrainerFixtures::TRAINER_1_REFERENCE,
                'reference' => self::DIET_PLAN_1_REFERENCE,
                'description' => self::DIET_PLAN_1_DESCRIPTION,
                'planName' => self::DIET_PLAN_1_NAME,
            ],
            'Plan_2' => [
                'trainer' => TrainerFixtures::TRAINER_1_REFERENCE,
                'reference' => self::DIET_PLAN_2_REFERENCE,
                'description' => self::DIET_PLAN_2_DESCRIPTION,
                'planName' => self::DIET_PLAN_2_NAME,
            ],
            'Plan_3' => [
                'trainer' => TrainerFixtures::TRAINER_2_REFERENCE,
                'reference' => self::DIET_PLAN_3_REFERENCE,
                'description' => self::DIET_PLAN_3_DESCRIPTION,
                'planName' => self::DIET_PLAN_3_NAME,
            ],
            'Plan_4' => [
                'trainer' => TrainerFixtures::TRAINER_2_REFERENCE,
                'reference' => self::DIET_PLAN_4_REFERENCE,
                'description' => self::DIET_PLAN_4_DESCRIPTION,
                'planName' => self::DIET_PLAN_4_NAME,
            ],
        ];
    }
}