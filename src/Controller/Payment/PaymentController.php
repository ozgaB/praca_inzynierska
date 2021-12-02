<?php
namespace App\Controller\Payment;

use App\Utils\PaymentPayU;
use App\Utils\CRUD\UserCRUD;
use App\Entity\Security\User;
use App\Form\Type\RegisterUserType;
use App\Entity\Subscription\SubscriptionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Utils\PaymentInsertToDB;

class PaymentController extends AbstractController
{
    /**
     * @Route("/make_order/{user}/{subscriptionType}", name="make_order")
     * @IsGranted("ROLE_TRAINER")
     */
    public function makeOrder(User $user, SubscriptionType $subscriptionType, PaymentPayU $payU, PaymentInsertToDB $paymentInsert)
    {
        $payment = $paymentInsert->insertPaymentToDB($user,$subscriptionType);
        if($payU->prepareAndExecuteOrder($payment))
        {
            dd('dziala');
        }


        return [
            'form' => $form->createView(),
        ];
    }
}
?>
