<?php

namespace App\Controller\DietPlan;

use App\Entity\Security\User;
use App\Form\Type\DietPlanType;
use App\Utils\CRUD\DietPlanCRUD;
use App\Form\Type\DietPlanDayType;
use App\Entity\DietPlan\DietPlanDay;
use App\Entity\DietPlan\DietPlanList;
use App\Entity\DietPlan\DietPlanAccess;
use App\Entity\Participant\Participant;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DietPlanDayRepository;
use App\Repository\ParticipantRepository;
use App\Repository\DietPlanListRepository;
use App\Repository\DietPlanAccessRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DietPlanController extends AbstractController
{

    /**
     * @Route("/show_diet_plan_list_trainer", name="show_diet_plan_list_trainer")
     * @Template()
     * @IsGranted("ROLE_TRAINER")
     */
    public function showDietPlanList(Security $security, DietPlanListRepository $dietPlanListRepository)
    {
        $currentTrainerId = $security->getUser()->getId();
        $dietPlanList = $dietPlanListRepository->getDietPlanListByTrainerId($currentTrainerId);
        return [
            'dietPlanList' => $dietPlanList
        ];
    }

        /**
     * @Route("/add_diet_plan", name="add_diet_plan")
     * @Template()
     * @IsGranted("ROLE_TRAINER")
     */
    public function addDietPlan(Security $security, Request $request, DietPlanCRUD $dietPlanCRUD)
    {
        $currentTrainerId = $security->getUser()->getId();
        $dietPlan = new DietPlanList();
        $dietPlan->setIdTrainer($currentTrainerId);
        $dietPlan->setCreatedAt();
        $form = $this->createForm(DietPlanType::class, $dietPlan);
        $form->handleRequest($request);
        $dietPlanCRUDResult = $dietPlanCRUD->addDietPlan($form,$currentTrainerId);
        if($dietPlanCRUDResult['result'] == 'success')
        {
            if ($form->getClickedButton() === $form->get('saveAndAdd')){
                $this->addFlash('success', 'Brawo Dodałeś nowy plan treningowy, zacznij wypełniać dni i ćwiczenia!');
                return $this->redirectToRoute('add_diet_plan_day', ['dietPlan' => $form->getData()->getId() ]);
            } else {
                $this->addFlash('success', 'Brawo Dodałeś nowy plan treningowy, teraz uzupełnij go o dni i ćwiczenia!');
                return $this->redirectToRoute('show_diet_plan_list_trainer');
            }
        } elseif ($dietPlanCRUDResult['result'] === 'faild') {
            $this->addFlash('danger', 'Nie udało się dodać planu treningowego!');
        } else {

        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/add_diet_plan_day/{dietPlan}", name="add_diet_plan_day")
     * @Template()
     * @IsGranted("ROLE_TRAINER")
     */
    public function addDietPlanDay(Request $request, DietPlanList $dietPlan, DietPlanCRUD $dietPlanCRUD)
    {

        $dietPlanDay = new DietPlanDay();
        $dietPlanDay->setDietPlan($dietPlan);
        $form = $this->createForm(DietPlanDayType::class, $dietPlanDay);
        $form->handleRequest($request);
        $dietPlanCRUDResult = $dietPlanCRUD->addDietPlanDay($form);
        if($dietPlanCRUDResult['result'] == 'success')
        {
            if ($form->getClickedButton() === $form->get('saveAndAdd')){
                $this->addFlash('success', 'Brawo Dodałeś nowy dzień treningowy, wypełniaj dalej!');
                return $this->redirectToRoute('add_diet_plan_day', ['dietPlan' => $dietPlan->getId() ]);
            } else {
                $this->addFlash('success', 'Brawo Dodałeś nowy plan treningowy!');
                return $this->redirectToRoute('show_diet_plan_list_trainer');
            }
        } elseif ($dietPlanCRUDResult['result'] === 'faild') {
            $this->addFlash('danger', 'Nie udało się dodać dnia treningowego!');
        } else {

        }
        return [
            'form' => $form->createView()
        ];
    }

     /**
     * @Route("/update_diet_plan_day/{dietPlan}/day/{dietPlanDay}", name="update_diet_plan_day")
     * @Template()
     * @IsGranted("ROLE_TRAINER")
     */
    public function updateDietPlanDay(
        Request $request,
        EntityManagerInterface $entityManager, 
        DietPlanDay $dietPlanDay, 
        DietPlanDayRepository $dietPlanDayRepository,
        DietPlanList $dietPlan,
        DietPlanCRUD $dietPlanCRUD
        )
    {
        $nextDayId = $dietPlanDayRepository->getNextDay($dietPlanDay->getId(),$dietPlan->getId());
        $previousDayId = $dietPlanDayRepository->getPreviousDay($dietPlanDay->getId(),$dietPlan->getId());
        $nextDay = $dietPlanDayRepository->findOneBy(['id' => $nextDayId]);
        $previousDay = $dietPlanDayRepository->findOneBy(['id' => $previousDayId]);
        $currentDietPlanDay = $dietPlanDay;
        $form = $this->createForm(DietPlanDayType::class, $dietPlanDay);
        $form->handleRequest($request);
        $dietPlanCRUDResult = $dietPlanCRUD->updateDietPlanDay($form, $dietPlanDay);
        if($dietPlanCRUDResult['result'] == 'success')
        {
            if ($form->getClickedButton() === $form->get('saveAndAdd')){
                $this->addFlash('success', 'Brawo edytowałeś dzień treningowy, wypełniaj dalej!');
                return $this->redirectToRoute('add_diet_plan_day', ['dietPlan' => $dietPlan->getId() ]);
            } else {
                $this->addFlash('success', 'Brawo edytowałeś plan treningowy!');
                return $this->redirectToRoute('show_diet_plan_list_trainer');
            }
        } elseif ($dietPlanCRUDResult['result'] === 'faild') {
            $this->addFlash('danger', 'Nie udało się dodać dnia treningowego!');
        } else {

        }
        return [
            'dietPlan' => $dietPlan,
            'currentDietPlanDay' => $currentDietPlanDay,
            'nextDay' => $nextDay,
            'previousDay' => $previousDay,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/show_diet_plan_access_list/{dietPlan}", name="show_diet_plan_access_list")
     * @Template()
     * @IsGranted("ROLE_TRAINER")
     */
    public function showDietPlanAccessList(ParticipantRepository $participantRepository, Security $security, DietPlanAccessRepository $dietPlanAccessRepository, DietPlanList $dietPlan)
    {
        $trainerId = $security->getUser()->getId();
        $participantList = $participantRepository->getParticipantsByTrainerIdWithoutAccessToDietPlan($trainerId,$dietPlan->getId());
        $accessList = $dietPlanAccessRepository->getAccessListByDietPlanId($dietPlan->getId());
        return [
            'dietPlan' => $dietPlan,
            'participantList' => $participantList,
            'accessList' => $accessList
        ];
    }
    /**
     * @Route("/add_access_to_diet_plan/{participantUser}/plan/{dietPlan}", name="add_access_to_diet_plan")
     * @IsGranted("ROLE_TRAINER")
     */
    public function addAccessToDietPlan(User $participantUser,DietPlanList $dietPlan, DietPlanCRUD $dietPlanCRUD)
    {
        if('success' === $dietPlanCRUD->addAccess($participantUser,$dietPlan))
        {
            $this->addFlash('success', 'Brawo nowy użytkownik dodany do twojego planu!');
            return $this->redirectToRoute('show_diet_plan_access_list',['dietPlan' => $dietPlan->getId()]);
        }
       $this->addFlash('danger', 'Nie udało się dodać dnia treningowego!');
       return $this->redirectToRoute('show_diet_plan_access_list',['dietPlan' => $dietPlan->getId()]);
    }
    /**
     *  @Route("/remove_access_from_diet_plan/{dietPlanAccess}/plan/{dietPlan}", name="remove_access_for_diet_plan")
     *  @IsGranted("ROLE_TRAINER")
     */
    public function removeAccessForDietPlan(DietPlanAccess $dietPlanAccess,DietPlanList $dietPlan, DietPlanCRUD $dietPlanCRUD)
    {
        if('success' === $dietPlanCRUD->removeAccess($dietPlanAccess))
        {
            $this->addFlash('success', 'Odpiąłeś tego uczestnika od planu!');
            return $this->redirectToRoute('show_diet_plan_access_list',['dietPlan' => $dietPlan->getId()]);
        }
       $this->addFlash('danger', 'Nie udało się odpiąć tego uczestnika!');
       return $this->redirectToRoute('show_diet_plan_access_list',['dietPlan' => $dietPlan->getId()]);
    }
    /**
     *  @Route("/show_diet_plan_list_for_user", name="show_diet_plan_list_for_user")
     *  @Template()
     *  @IsGranted("ROLE_USER")
     */
    public function showDietPlanListForUser(DietPlanListRepository $dietPlanListRepository, Security $security)
    {
        $userId = $security->getUser()->getId();
        $dietPlanList = $dietPlanListRepository->getDietPlanListByUserId($userId);
        
        return [
            'dietPlanList' => $dietPlanList
        ];
    }
    /**
     * @Route("/show_diet_plan/{dietPlan}/day/{dietPlanDay}", name="show_diet_plan_day")
     * @Template()
     * @IsGranted("ROLE_USER")
     */
    public function showDietPlanDay(DietPlanDayRepository $dietPlanDayRepository, DietPlanDay $dietPlanDay, DietPlanListRepository $dietPlanListRepository,DietPlanList $dietPlan)
    {
        $dietPlanListDays = $dietPlanListRepository->getDaysByDietPlanId($dietPlan->getId());
        $dietPlanProducts = $dietPlanDayRepository->getDietPlanDayById($dietPlanDay);
        $dietPlanProductsSum = $dietPlanDayRepository->getDietPlanDayProductSumById($dietPlanDay);
        $currentDietPlanDay = $dietPlanDay;
        $nextDayId = $dietPlanDayRepository->getNextDay($dietPlanDay->getId(),$dietPlan->getId());
        $previousDayId = $dietPlanDayRepository->getPreviousDay($dietPlanDay->getId(),$dietPlan->getId());
        $nextDay = $dietPlanDayRepository->findOneBy(['id' => $nextDayId]);
        $previousDay = $dietPlanDayRepository->findOneBy(['id' => $previousDayId]);

        return [
            'nextDay' => $nextDay,
            'previousDay' => $previousDay,
            'currentDay' => $currentDietPlanDay,
            'dietPlan' => $dietPlan,
            'dietPlanListDays' => $dietPlanListDays,
            'dietPlanProducts' => $dietPlanProducts,
            'dietPlanProductsSum' => $dietPlanProductsSum,
        ];
    }
    /**
     * @Route("/remove_diet_plan/{dietPlan}", name="remove_diet_plan")
     * @IsGranted("ROLE_TRAINER")
     */
    public function removeDietPlan(DietPlanCRUD $dietPlanCRUD,DietPlanList $dietPlan)
    {
        if('success' === $dietPlanCRUD->removeDietPlanWithAllDependencies($dietPlan))
        {
            $this->addFlash('success', 'Usunąłeś ten plan!');
            return $this->redirectToRoute('show_diet_plan_list_trainer');
        }
       $this->addFlash('danger', 'Nie udało się usunąć tego planu!');
       return $this->redirectToRoute('show_diet_plan_list_trainer');
    }
}