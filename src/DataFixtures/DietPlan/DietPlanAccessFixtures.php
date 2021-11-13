<?php

namespace App\DataFixtures\DietPlan;

use App\Entity\Security\User;
use App\Entity\DietPlan\DietPlanAccess;
use App\Entity\Participant\Participant;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Users\AdminFixtures;
use App\DataFixtures\Users\TrainerFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Users\StandardUserFixtures;
use App\DataFixtures\DietPlan\DietPlanDayFixtures;
use App\DataFixtures\DietPlan\DietPlanListFixtures;
use App\DataFixtures\Participant\ParticipantFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DietPlanAccessFixtures extends Fixture implements DependentFixtureInterface
{
    const DIET_PLAN_ACCESS_1_REFERENCE = 'diet-plan-access-1-reference';
    const DIET_PLAN_ACCESS_2_REFERENCE = 'diet-plan-access-2-reference';
    const DIET_PLAN_ACCESS_3_REFERENCE = 'diet-plan-access-3-reference';
    const DIET_PLAN_ACCESS_4_REFERENCE = 'diet-plan-access-4-reference';
    const DIET_PLAN_ACCESS_5_REFERENCE = 'diet-plan-access-5-reference';
    const DIET_PLAN_ACCESS_6_REFERENCE = 'diet-plan-access-6-reference';
    const DIET_PLAN_ACCESS_7_REFERENCE = 'diet-plan-access-7-reference';
    const DIET_PLAN_ACCESS_8_REFERENCE = 'diet-plan-access-8-reference';


    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach($this->getTrainerData() as $access)
        {
            $data = new DietPlanAccess();
            $data
            ->setIdUser($this->getReference($access['user'])->getId())
            ->setIdDietPlan($this->getReference($access['dietPlan'])->getId());
            $manager->persist($data);
            $this->addReference($access['reference'], $data);
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
            DietPlanDayFixtures::class,
        ];
    }

    public function getTrainerData(): array
    {
        return [
            'Diet_plan_access_1' => [
                'user' => StandardUserFixtures::USER_1_REFERENCE,
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_1_REFERENCE,
                'reference' => self::DIET_PLAN_ACCESS_1_REFERENCE,
            ],
            'Diet_plan_access_2' => [
                'user' => StandardUserFixtures::USER_2_REFERENCE,
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_1_REFERENCE,
                'reference' => self::DIET_PLAN_ACCESS_2_REFERENCE,
            ],
            'Diet_plan_access_3' => [
                'user' => StandardUserFixtures::USER_1_REFERENCE,
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_2_REFERENCE,
                'reference' => self::DIET_PLAN_ACCESS_3_REFERENCE,
            ],
            'Diet_plan_access_4' => [
                'user' => StandardUserFixtures::USER_2_REFERENCE,
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_2_REFERENCE,
                'reference' => self::DIET_PLAN_ACCESS_4_REFERENCE,
            ],
            'Diet_plan_access_5' => [
                'user' => StandardUserFixtures::USER_1_REFERENCE,
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_3_REFERENCE,
                'reference' => self::DIET_PLAN_ACCESS_5_REFERENCE,
            ],
            'Diet_plan_access_6' => [
                'user' => StandardUserFixtures::USER_2_REFERENCE,
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_3_REFERENCE,
                'reference' => self::DIET_PLAN_ACCESS_6_REFERENCE,
            ],
            'Diet_plan_access_7' => [
                'user' => StandardUserFixtures::USER_1_REFERENCE,
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_4_REFERENCE,
                'reference' => self::DIET_PLAN_ACCESS_7_REFERENCE,
            ],
            'Diet_plan_access_8' => [
                'user' => StandardUserFixtures::USER_2_REFERENCE,
                'dietPlan' => DietPlanListFixtures::DIET_PLAN_4_REFERENCE,
                'reference' => self::DIET_PLAN_ACCESS_8_REFERENCE,
            ],
        ];
    }
}