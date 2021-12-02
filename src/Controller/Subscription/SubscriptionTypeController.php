<?php
namespace App\Controller\Subscription;

use App\Form\Type\RegisterUserType;
use App\Repository\MainPageRepository;
use App\Form\Type\SubscriptionTypeType;
use App\Utils\CRUD\SubscriptionTypeCRUD;
use App\Entity\Subscription\SubscriptionType;
use App\Repository\SubscriptionTypeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class SubscriptionTypeController
 */
class SubscriptionTypeController extends AbstractController
{
    /**
     *@Route("/subscription_type_list", name="subscription_type_list")
     *@Template()
     *@IsGranted("ROLE_ADMIN")
     */
    public function subscriptionTypeList(SubscriptionTypeRepository $subscriptionTypeRepository)
    {
        $subscriptionTypeList = $subscriptionTypeRepository->getSubscriptionTypeList();

        return [
            'subscriptionTypeList' => $subscriptionTypeList
    ];
    }

    /**
     *@Route("/add_subscription_type", name="add_subscription_type")
     *@IsGranted("ROLE_ADMIN")
     *@Template()
     */
    public function addSubscriptionType(SubscriptionTypeCRUD $subscriptionTypeCRUD)
    {
        $subscriptionType = new SubscriptionType();
        $subscriptionTypeForm = $this->createForm(SubscriptionTypeType::class, $subscriptionType);
        if('success' === $subscriptionTypeCRUD->addOrUpdateSubscriptionType($subscriptionTypeForm))
        {
            $this->addFlash('success', 'Brawo nowy typ subskrypcji został dodany!');
            return $this->redirectToRoute('subscription_type_list');
        }
        
        return [
            'subscriptionTypeForm' => $subscriptionTypeForm->createView(),
    ];
    }

    /**
     *@Route("/edit_subscription_type/{subscriptionType}", name="edit_subscription_type")
     *@IsGranted("ROLE_ADMIN")
     *@Template()
     */
    public function editSubscriptionType(SubscriptionType $subscriptionType, SubscriptionTypeCRUD $subscriptionTypeCRUD)
    {
        $subscriptionTypeForm = $this->createForm(SubscriptionTypeType::class, $subscriptionType);
        if('success' === $subscriptionTypeCRUD->addOrUpdateSubscriptionType($subscriptionTypeForm))
        {
            $this->addFlash('success', 'Brawo typ subskrypcji został edytowany!');
            return $this->redirectToRoute('subscription_type_list');
        }

        return [
            'subscriptionTypeForm' => $subscriptionTypeForm->createView(),
    ];
    }

    /**
     *@Route("/remove_subscription_type/{subscriptionType}", name="remove_subscription_type")
     *@IsGranted("ROLE_ADMIN")
     */
    public function removeSubscriptionType(SubscriptionType $subscriptionType, SubscriptionTypeCRUD $subscriptionTypeCRUD)
    {
        if('success' === $subscriptionTypeCRUD->removeSubscriptionType($subscriptionType))
        {
            $this->addFlash('success', 'Udało się usunąć typ subskrypcji!');
            return $this->redirectToRoute('subscription_type_list');
        }
        $this->addFlash('danger', 'Nie udało się usunąć typu subskrypcji!');
        return $this->redirectToRoute('subscription_type_list');
    }
}
