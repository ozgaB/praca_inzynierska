<?php

namespace App\Utils\CRUD;

use Exception;
use App\Entity\Security\User;
use App\Entity\DietPlan\DietPlanDay;
use App\Entity\DietPlan\DietPlanList;
use App\Entity\DietPlan\DietPlanAccess;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DietPlanDayRepository;
use App\Repository\DietPlanListRepository;
use App\Repository\DietPlanProductRepository;
use Doctrine\Common\Collections\ArrayCollection;

class DietPlanCRUD
{
    private string $result = 'none';
    private EntityManagerInterface $entityManager;
    private DietPlanListRepository $dietPlanList;
    private DietPlanDayRepository $dietPlanDayRepository;
    private DietPlanProductsRepository $dietPlanProductsRepository;

    public function __construct(EntityManagerInterface $entityManager, DietPlanListRepository $dietPlanListRepository, DietPlanDayRepository $dietPlanDayRepository, DietPlanProductRepository $dietPlanProductRepository)
    {
        $this->entityManager = $entityManager;
        $this->dietPlanListRepository = $dietPlanListRepository;
        $this->dietPlanDayRepository = $dietPlanDayRepository;
        $this->dietPlanProductRepository = $dietPlanProductRepository;
    }

    public function addDietPlanDay($form)
    {

        if ($form->isSubmitted() && $form->isValid()) {
            $dietPlan = $form->getData();
            try {
                $this->entityManager->persist($dietPlan);
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

    public function addDietPlan($form, int $currentTrainerId)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $dietPlanDay = $form->getData();
            try {
                $this->entityManager->persist($dietPlanDay);
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

    public function addAccess(User $participantUser,DietPlanList $dietPlan)
    {
        $accessDietPlan = new DietPlanAccess();
        $accessDietPlan->setIdDietPLan($dietPlan->getId());
        $accessDietPlan->setIdUser($participantUser->getId());
        try {
            $this->entityManager->persist($accessDietPlan);
            $this->entityManager->flush();
            $this->result = 'success';
        } catch (Exception $e) {
            $this->result = 'faild';
        }

        return $this->result;
    }

    public function removeAccess(DietPlanAccess $dietPlanAccess)
    {
        try {
            $this->entityManager->remove($dietPlanAccess);
            $this->entityManager->flush();
            $this->result = 'success';
        } catch (Exception $e) {
            $this->result = 'faild';
        }

        return $this->result;
    }

    public function removeDietPlanWithAllDependencies(DietPlanList $dietPlan)
    {
        try {
            $this->entityManager->remove($dietPlan);
            $this->entityManager->flush();
            $this->result = 'success';
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $this->result;
    }

    public function updateDietPlanDay($form,DietPlanDay $dietPlanDay)
    {
        $originalProduct = new ArrayCollection();
        //pobiera i dodaje do kolekcji
        foreach ($dietPlanDay->getDietPlanProduct() as $dietPlanProduct)
        {
            $originalProduct->add($dietPlanProduct);
        }
        if($form->isSubmitted()){
            foreach ($originalProduct as $product)
            {
                if(false === $dietPlanDay->getDietPlanProduct()->contains($product))
                {
                    $this->entityManager->remove($product);
                }
            }
            $this->entityManager->persist($dietPlanDay);
            try {
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
}
?>