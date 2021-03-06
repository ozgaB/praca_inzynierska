<?php

namespace App\Utils\CRUD;

use Exception;
use App\Entity\Security\User;
use App\Entity\Participant\Participant;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TrainingPlan\TrainingPlanDay;
use App\Entity\TrainingPlan\TrainingPlanList;
use App\Repository\TrainingPlanDayRepository;
use App\Repository\TrainingPlanListRepository;
use App\Entity\TrainingPlan\TrainingPlanAccess;
use Doctrine\Common\Collections\ArrayCollection;
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
                throw new Exception($e->getMessage());
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

    public function removeTrainingPlanWithAllDependencies(TrainingPlanList $trainingPlan)
    {
        try {
            $this->entityManager->remove($trainingPlan);
            $this->entityManager->flush();
            $this->result = 'success';
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            $this->result = 'faild';
        }

        return $this->result;
    }

    public function updateTrainingPlanDay($form,TrainingPlanDay $trainingPlanDay)
    {
        $originalExercise = new ArrayCollection();
        //pobiera i dodaje do kolekcji
        foreach ($trainingPlanDay->getTrainingPlanExercise() as $trainingPlanExercise)
        {
            $originalExercise->add($trainingPlanExercise);
        }
        if($form->isSubmitted()){
            foreach ($originalExercise as $exercise)
            {
                if(false === $trainingPlanDay->getTrainingPlanExercise()->contains($exercise))
                {
                    $this->entityManager->remove($exercise);
                }
            }
            $this->entityManager->persist($trainingPlanDay);
            try {
                $this->entityManager->flush();
                $this->result = 'success';
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
                $this->result = 'faild';
            }
        }

        return [
            'form' => $form,
            'result' => $this->result,
        ];
    }
}
?>