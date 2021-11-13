<?php
namespace App\DataFixtures\DietPlan;

use App\Entity\Security\User;
use App\Entity\DietPlan\DietPlanDay;
use App\Entity\DietPlan\DietPlanList;
use App\Entity\Participant\Participant;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Users\AdminFixtures;
use App\DataFixtures\Users\TrainerFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Users\StandardUserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\DataFixtures\Participant\ParticipantFixtures;

class DietPlanDayFixtures extends Fixture implements DependentFixtureInterface
{
    const DIET_PLAN_DAY_1_REFERENCE = 'diet-plan-day-1-reference';
    const DIET_PLAN_DAY_2_REFERENCE = 'diet-plan-day-2-reference';
    const DIET_PLAN_DAY_3_REFERENCE = 'diet-plan-day-3-reference';
    const DIET_PLAN_DAY_4_REFERENCE = 'diet-plan-day-4-reference';

    const DIET_PLAN_DAY_1_NAME = 'Poniedziałek';
    const DIET_PLAN_DAY_2_NAME = 'Wtorek';
    const DIET_PLAN_DAY_3_NAME = 'Dzień A';
    const DIET_PLAN_DAY_4_NAME = 'Dzień B';

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach($this->getTrainerData() as $data)
        {
            $dietPlan = new DietPlanDay();
            $dietPlan
            ->setDietPlan($this->getReference($data['dietPlan']))
            ->setDayName($data['dayName']);
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
            ParticipantFixtures::class,
            DietPlanListFixtures::class,
        ];
    }

    public function getTrainerData(): array
    {
        return [
            'Day_1' => [
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_1_REFERENCE,
                'reference' => self::DIET_PLAN_DAY_1_REFERENCE,
                'dayName' => self::DIET_PLAN_DAY_1_NAME,
            ],
            'Day_2' => [
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_1_REFERENCE,
                'reference' => self::DIET_PLAN_DAY_2_REFERENCE,
                'dayName' => self::DIET_PLAN_DAY_2_NAME,
            ],
            'Day_3' => [
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_2_REFERENCE,
                'reference' => self::DIET_PLAN_DAY_3_REFERENCE,
                'dayName' => self::DIET_PLAN_DAY_3_NAME,
            ],
            'Day_4' => [
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_2_REFERENCE,
                'reference' => self::DIET_PLAN_DAY_4_REFERENCE,
                'dayName' => self::DIET_PLAN_DAY_4_NAME,
            ],
        ];
    }
}