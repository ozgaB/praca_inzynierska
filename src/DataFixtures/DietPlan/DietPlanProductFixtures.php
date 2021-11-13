<?php
namespace App\DataFixtures\DietPlan;

use App\Entity\Security\User;
use App\Entity\DietPlan\DietPlanDay;
use App\Entity\DietPlan\DietPlanList;
use App\Entity\Participant\Participant;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Users\AdminFixtures;
use App\Entity\DietPlan\DietPlanProducts;
use App\DataFixtures\Users\TrainerFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Users\StandardUserFixtures;
use App\DataFixtures\Participant\ParticipantFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DietPlanProductFixtures extends Fixture implements DependentFixtureInterface
{
    const DIET_PLAN_PRODUCT_1_REFERENCE = 'diet-plan-product-1-reference';
    const DIET_PLAN_PRODUCT_2_REFERENCE = 'diet-plan-product-2-reference';
    const DIET_PLAN_PRODUCT_3_REFERENCE = 'diet-plan-product-3-reference';
    const DIET_PLAN_PRODUCT_4_REFERENCE = 'diet-plan-product-4-reference';
    const DIET_PLAN_PRODUCT_5_REFERENCE = 'diet-plan-product-5-reference';
    const DIET_PLAN_PRODUCT_6_REFERENCE = 'diet-plan-product-6-reference';
    const DIET_PLAN_PRODUCT_7_REFERENCE = 'diet-plan-product-7-reference';
    const DIET_PLAN_PRODUCT_8_REFERENCE = 'diet-plan-product-8-reference';
    const DIET_PLAN_PRODUCT_9_REFERENCE = 'diet-plan-product-9-reference';
    const DIET_PLAN_PRODUCT_10_REFERENCE = 'diet-plan-product-10-reference';

    const DIET_PLAN_PRODUCT_MEAL_GROUP_1 = 'śniadanie';
    const DIET_PLAN_PRODUCT_MEAL_GROUP_2 = 'obiad';
    const DIET_PLAN_PRODUCT_MEAL_GROUP_3 = 'kolacja';

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach($this->getTrainerData() as $data)
        {
            $dietPlan = new DietPlanProducts();
            $dietPlan
            ->setDietPlanDay($this->getReference($data['dietPlanDay']))
            ->setMealGroup($data['mealGroup'])
            ->setProductName($data['productName'])
            ->setProtein($data['protein'])
            ->setCarbo($data['carbo'])
            ->setFat($data['fat'])
            ->setKcl($data['kcl']);
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
            DietPlanDayFixtures::class,
        ];
    }

    public function getTrainerData(): array
    {
        return [
            'Product_1' => [
                'dietPlanDay' => DietPlanDayFixtures::DIET_PLAN_DAY_1_REFERENCE,
                'reference' => self::DIET_PLAN_PRODUCT_1_REFERENCE,
                'mealGroup' => self::DIET_PLAN_PRODUCT_MEAL_GROUP_1,
                'productName' => 'Banan',
                'protein' => 5,
                'carbo' => 25,
                'fat' => 21,
                'kcl' => 280,
            ],
            'Product_2' => [
                'dietPlanDay' => DietPlanDayFixtures::DIET_PLAN_DAY_1_REFERENCE,
                'reference' => self::DIET_PLAN_PRODUCT_2_REFERENCE,
                'mealGroup' => self::DIET_PLAN_PRODUCT_MEAL_GROUP_2,
                'productName' => 'Omlet',
                'protein' => 7,
                'carbo' => 10,
                'fat' => 15,
                'kcl' => 270,
            ],
            'Product_3' => [
                'dietPlanDay' => DietPlanDayFixtures::DIET_PLAN_DAY_2_REFERENCE,
                'reference' => self::DIET_PLAN_PRODUCT_3_REFERENCE,
                'mealGroup' => self::DIET_PLAN_PRODUCT_MEAL_GROUP_1,
                'productName' => 'Kasza',
                'protein' => 23,
                'carbo' => 11,
                'fat' => 7,
                'kcl' => 344,
            ],
            'Product_4' => [
                'dietPlanDay' => DietPlanDayFixtures::DIET_PLAN_DAY_2_REFERENCE,
                'reference' => self::DIET_PLAN_PRODUCT_4_REFERENCE,
                'mealGroup' => self::DIET_PLAN_PRODUCT_MEAL_GROUP_3,
                'productName' => 'Płatki zbożowe',
                'protein' => 14,
                'carbo' => 11,
                'fat' => 44,
                'kcl' => 340,
            ],
            'Product_5' => [
                'dietPlanDay' => DietPlanDayFixtures::DIET_PLAN_DAY_3_REFERENCE,
                'reference' => self::DIET_PLAN_PRODUCT_5_REFERENCE,
                'mealGroup' => self::DIET_PLAN_PRODUCT_MEAL_GROUP_1,
                'productName' => 'Sałatka jażynowa',
                'protein' => 21,
                'carbo' => 11,
                'fat' => 10,
                'kcl' => 333,
            ],
            'Product_6' => [
                'dietPlanDay' => DietPlanDayFixtures::DIET_PLAN_DAY_3_REFERENCE,
                'reference' => self::DIET_PLAN_PRODUCT_6_REFERENCE,
                'mealGroup' => self::DIET_PLAN_PRODUCT_MEAL_GROUP_2,
                'productName' => 'Kanapki',
                'protein' => 5,
                'carbo' => 10,
                'fat' => 10,
                'kcl' => 250,
            ],
            'Product_7' => [
                'dietPlanDay' => DietPlanDayFixtures::DIET_PLAN_DAY_4_REFERENCE,
                'reference' => self::DIET_PLAN_PRODUCT_7_REFERENCE,
                'mealGroup' => self::DIET_PLAN_PRODUCT_MEAL_GROUP_2,
                'productName' => 'Owsianka',
                'protein' => 17,
                'carbo' => 5,
                'fat' => 5,
                'kcl' => 115,
            ],
            'Product_8' => [
                'dietPlanDay' => DietPlanDayFixtures::DIET_PLAN_DAY_4_REFERENCE,
                'reference' => self::DIET_PLAN_PRODUCT_8_REFERENCE,
                'mealGroup' => self::DIET_PLAN_PRODUCT_MEAL_GROUP_3,
                'productName' => 'Kurczak pieczony',
                'protein' => 25,
                'carbo' => 10,
                'fat' => 5,
                'kcl' => 250,
            ],
            'Product_9' => [
                'dietPlanDay' => DietPlanDayFixtures::DIET_PLAN_DAY_3_REFERENCE,
                'reference' => self::DIET_PLAN_PRODUCT_9_REFERENCE,
                'mealGroup' => self::DIET_PLAN_PRODUCT_MEAL_GROUP_1,
                'productName' => 'Miarka Białka',
                'protein' => 25,
                'carbo' => 0,
                'fat' => 0,
                'kcl' => 250,
            ],
            'Product_10' => [
                'dietPlanDay' => DietPlanDayFixtures::DIET_PLAN_DAY_3_REFERENCE,
                'reference' => self::DIET_PLAN_PRODUCT_10_REFERENCE,
                'mealGroup' => self::DIET_PLAN_PRODUCT_MEAL_GROUP_3,
                'productName' => 'Naleśniki',
                'protein' => 21,
                'carbo' => 10,
                'fat' => 15,
                'kcl' => 510,
            ],
        ];
    }
}