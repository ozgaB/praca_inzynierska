<?php

namespace App\Controller\TrainingPlan;

use App\Entity\Security\User;
use App\Form\Type\TrainingPlanType;
use App\Utils\CRUD\TrainingPlanCRUD;
use App\Form\Type\TrainingPlanDayType;
use App\Entity\Participant\Participant;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use App\Entity\TrainingPlan\TrainingPlanDay;
use App\Entity\TrainingPlan\TrainingPlanList;
use App\Repository\TrainingPlanDayRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Repository\TrainingPlanListRepository;
use App\Entity\TrainingPlan\TrainingPlanAccess;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrainingPlanAccessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\TrainingPlan\TrainingPlanExercise;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrainingPlanController extends AbstractController
{

    /**
     * @Route("/show_training_plan_list_trainer", name="show_training_plan_list_trainer")
     * @Template()
     */
    public function showTrainingPlanList(Security $security, TrainingPlanListRepository $trainingPlanListRepository)
    {
        $currentTrainerId = $security->getUser()->getId();
        $trainingPlanList = $trainingPlanListRepository->getTrainingPlanListByTrainerId($currentTrainerId);
        return [
            'trainingPlanList' => $trainingPlanList
        ];
    }

    /**
     * @Route("/add_training_plan", name="add_training_plan")
     * @Template()
     */
    public function addTrainingPlan(Security $security, Request $request, TrainingPlanCRUD $trainingPlanCRUD)
    {
        $currentTrainerId = $security->getUser()->getId();
        $trainingPlan = new TrainingPlanList();
        $trainingPlan->setIdTrainer($currentTrainerId);
        $trainingPlan->setCreatedAt();
        $form = $this->createForm(TrainingPlanType::class, $trainingPlan);
        $form->handleRequest($request);
        $trainingPlanCRUDResult = $trainingPlanCRUD->addTrainingPlan($form,$currentTrainerId);
        if($trainingPlanCRUDResult['result'] == 'success')
        {
            if ($form->getClickedButton() === $form->get('saveAndAdd')){
                $this->addFlash('success', 'Brawo Dodałeś nowy plan treningowy, zacznij wypełniać dni i ćwiczenia!');
                return $this->redirectToRoute('add_training_plan_day', ['trainingPlan' => $form->getData()->getId() ]);
            } else {
                $this->addFlash('success', 'Brawo Dodałeś nowy plan treningowy, teraz uzupełnij go o dni i ćwiczenia!');
                return $this->redirectToRoute('show_training_plan_list_trainer');
            }
        } elseif ($trainingPlanCRUDResult['result'] === 'faild') {
            $this->addFlash('danger', 'Nie udało się dodać planu treningowego!');
        } else {

        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/add_training_plan_day/{trainingPlan}", name="add_training_plan_day")
     * @Template()
     */
    public function addTrainingPlanDay(Request $request, TrainingPlanList $trainingPlan, TrainingPlanCRUD $trainingPlanCRUD)
    {

        $trainingPlanDay = new TrainingPlanDay();
        $trainingPlanDay->setIdTrainingPlan($trainingPlan->getId());
        $form = $this->createForm(TrainingPlanDayType::class, $trainingPlanDay);
        $form->handleRequest($request);
        $trainingPlanCRUDResult = $trainingPlanCRUD->addTrainingPlanDay($form);
        if($trainingPlanCRUDResult['result'] == 'success')
        {
            if ($form->getClickedButton() === $form->get('saveAndAdd')){
                $this->addFlash('success', 'Brawo Dodałeś nowy dzień treningowy, wypełniaj dalej!');
                return $this->redirectToRoute('add_training_plan_day', ['trainingPlan' => $trainingPlan->getId() ]);
            } else {
                $this->addFlash('success', 'Brawo Dodałeś nowy plan treningowy!');
                return $this->redirectToRoute('show_training_plan_list_trainer');
            }
        } elseif ($trainingPlanCRUDResult['result'] === 'faild') {
            $this->addFlash('danger', 'Nie udało się dodać dnia treningowego!');
        } else {

        }
        return [
            'form' => $form->createView()
        ];
    }
        /**
     * @Route("/update_training_plan_day", name="update_training_plan_day")
     * @Template()
     */
    public function updateTrainingPlanDay(Request $request, EntityManagerInterface $entityManager)
    {

        $trainingPlanDay = new TrainingPlanDay();
        $trainingPlanDay->setDayName('dupa');
        $trainingPlanDay->setIdTrainingPlan(1);
        $form = $this->createForm(TrainingPlanDayType::class, $trainingPlanDay);
        $form->handleRequest($request);

        $originalExercise = new ArrayCollection();

        //pobiera i dodaje do kolekcji
        foreach ($trainingPlanDay->getTrainingPlanExercise() as $trainingPlanExercise)
        {
            $originalExercise->add($trainingPlanExercise);
        }

        if($form->isSubmitted()){
                        //sprawdza czy orginalna tablica nadal zawiera stare elementy
                        foreach ($originalExercise as $exercise)
                        {
                            if(false === $trainingPlanDay->getTrainingPlanExercise()->contains($exercise))
                            {
                                $entityManager->remove($exercise);
                            }
                        }
            $entityManager->persist($trainingPlanDay);
            $entityManager->flush();
        }
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/show_training_plan_access_list/{trainingPlan}", name="show_training_plan_access_list")
     * @Template()
     */
    public function showTrainingPlanAccessList(ParticipantRepository $participantRepository, Security $security, TrainingPlanAccessRepository $trainingPlanAccessRepository, TrainingPlanList $trainingPlan)
    {
        $trainerId = $security->getUser()->getId();
        $participantList = $participantRepository->getParticipantsByTrainerIdWithoutAccess($trainerId,$trainingPlan->getId());
        $accessList = $trainingPlanAccessRepository->getAccessListByTrainingPlanId($trainingPlan->getId());
        return [
            'trainingPlan' => $trainingPlan,
            'participantList' => $participantList,
            'accessList' => $accessList
        ];
    }
    /**
     * @Route("/add_access_to_training_plan/{participantUser}/plan/{trainingPlan}", name="add_access_to_training_plan")
     * 
     */
    public function addAccessToTrainingPlan(User $participantUser,TrainingPlanList $trainingPlan, TrainingPlanCRUD $trainingPlanCRUD)
    {
        if('success' === $trainingPlanCRUD->addAccess($participantUser,$trainingPlan))
        {
            $this->addFlash('success', 'Brawo nowy użytkownik dodany do twojego planu!');
            return $this->redirectToRoute('show_training_plan_access_list',['trainingPlan' => $trainingPlan->getId()]);
        }
       $this->addFlash('danger', 'Nie udało się dodać dnia treningowego!');
       return $this->redirectToRoute('show_training_plan_access_list',['trainingPlan' => $trainingPlan->getId()]);
    }
    /**
     *  @Route("/remove_access_from_training_plan/{trainingPlanAccess}/plan/{trainingPlan}", name="remove_access_for_training_plan")
     */
    public function removeAccessForTrainingPlan(TrainingPlanAccess $trainingPlanAccess,TrainingPlanList $trainingPlan, TrainingPlanCRUD $trainingPlanCRUD)
    {
        if('success' === $trainingPlanCRUD->removeAccess($trainingPlanAccess))
        {
            $this->addFlash('success', 'Odpiąłeś tego uczestnika od planu!');
            return $this->redirectToRoute('show_training_plan_access_list',['trainingPlan' => $trainingPlan->getId()]);
        }
       $this->addFlash('danger', 'Nie udało się odpiąć tego uczestnika!');
       return $this->redirectToRoute('show_training_plan_access_list',['trainingPlan' => $trainingPlan->getId()]);
    }
    /**
     *  @Route("/show_training_plan_list_for_user", name="show_training_plan_list_for_user")
     *  @Template()
     */
    public function showTrainingPlanListForUser(TrainingPlanListRepository $trainingPlanListRepository, Security $security)
    {
        $userId = $security->getUser()->getId();
        $trainingPlanList = $trainingPlanListRepository->getTrainingPlanListByUserId($userId);
        
        return [
            'trainingPlanList' => $trainingPlanList
        ];
    }
    /**
     * @Route("/show_training_plan/{trainingPlan}/day/{trainingPlanDay}", name="show_training_plan_day")
     * @Template()
     */
    public function showTrainingPlanDay(TrainingPlanDayRepository $trainingPlanDayRepository, TrainingPlanDay $trainingPlanDay, TrainingPlanListRepository $trainingPlanListRepository,TrainingPlanList $trainingPlan)
    {
        $days = $trainingPlanListRepository->getDaysByTrainingPlanId($trainingPlan->getId());
        $trainingPlanDays = $trainingPlanDayRepository->getTrainingPlanDayById($trainingPlanDay);

        return [
            'days' => $days,
            'trainingPlanDays' => $trainingPlanDays
        ];
    }
}
?>