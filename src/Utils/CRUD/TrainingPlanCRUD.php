<?php

namespace App\Utils\CRUD;

use App\Entity\Security\User;
use App\Entity\Participant\Participant;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TrainingPlan\TrainingPlanList;
use App\Repository\TrainingPlanDayRepository;
use App\Repository\TrainingPlanListRepository;
use App\Entity\TrainingPlan\TrainingPlanAccess;
use App\Repository\TrainingPlanExerciseRepository;

class TrainingPlanCRUD
{
    private string $result = 'none';
    private EntityManagerInterface $entityManager;
    private TrainingPlanListRepository $trainingPlanList;
    private TrainingPlanDayRepository $trainingPlanDayRepository;
    private TrainingPlanExerciseRepository $trainingPlanExerciseRepository;

    public function __construct(EntityManagerInterface $entityManager, TrainingPlanListRepository $trainingPlanListRepository, TrainingPlanDayRepository $trainingPlanDayRepository, TrainingPlanExerciseRepository $trainingPlanExerciseRepository)
    {
        $this->entityManager = $entityManager;
        $this->trainingPlanListRepository = $trainingPlanListRepository;
        $this->trainingPlanDayRepository = $trainingPlanDayRepository;
        $this->trainingPlanExerciseRepository = $trainingPlanExerciseRepository;
    }

    public function addTrainingPlanDay($form)
    {

        if ($form->isSubmitted() && $form->isValid()) {
            $trainingPlan = $form->getData();
            try {
                $this->entityManager->persist($trainingPlan);
                $this->entityManager->flush();
                $this->result = 'success';
            } catch (Exception $e) {
                $this->result = 'faild';
            }
        }
        return [
            'form' => $form,
            'result' => $this->result,
        ];
    }

    public function addTrainingPlan($form, int $currentTrainerId)
    {
        $result = 'none';

        if ($form->isSubmitted() && $form->isValid()) {
            $trainingPlanDay = $form->getData();
            try {
                $this->entityManager->persist($trainingPlanDay);
                $this->entityManager->flush();
                $this->result = 'success';
            } catch (Exception $e) {
                $this->result = 'faild';
            }
        }
        return [
            'form' => $form,
            'result' => $this->result,
        ];
    }

    public function addAccess(User $participantUser,TrainingPlanList $trainingPlan)
    {
        $accessTrainingPlan = new TrainingPlanAccess();
        $accessTrainingPlan->setIdTrainingPLan($trainingPlan->getId());
        $accessTrainingPlan->setIdUser($participantUser->getId());
        try {
            $this->entityManager->persist($accessTrainingPlan);
            $this->entityManager->flush();
            $this->result = 'success';
        } catch (Exception $e) {
            $this->result = 'faild';
        }

        return $this->result;
    }

    public function removeAccess(TrainingPlanAccess $trainingPlanAccess)
    {
        try {
            $this->entityManager->remove($trainingPlanAccess);
            $this->entityManager->flush();
            $this->result = 'success';
        } catch (Exception $e) {
            $this->result = 'faild';
        }

        return $this->result;
    }
}
?>