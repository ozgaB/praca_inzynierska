<?php
namespace App\Controller\Payment;

use App\Utils\PaymentPayU;
use App\Utils\CRUD\UserCRUD;
use App\Entity\Security\User;
use App\Utils\PaymentInsertToDB;
use App\Form\Type\RegisterUserType;
use App\Repository\PaymentRepository;
use App\Entity\Subscription\SubscriptionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\Payment\Payment;

class PaymentController extends AbstractController
{
    /**
     * @Route("/make_order/{user}/{subscriptionType}", name="make_order")
     * @IsGranted("ROLE_TRAINER")
     */
    public function makeOrder(User $user, SubscriptionType $subscriptionType, PaymentPayU $payU, PaymentInsertToDB $paymentInsert)
    {
        $payment = $paymentInsert->insertPaymentToDB($user,$subscriptionType);
        $response = $payU->prepareAndExecuteOrder($payment);

        return new RedirectResponse($response->getResponse()->redirectUri);
    }

    /**
     * @Route("/notify_order", name="notify_order")
     * @IsGranted("ROLE_TRAINER")
     */
    public function notifyOrder(PaymentPayU $payU)
    {
        dd('tutaj');
        $payU->getNotify(); 

        return new Response('OK',200);
    }

    /**
     * @Route("/list_order", name="list_order")
     * @IsGranted("ROLE_TRAINER")
     * @Template()
     */
    public function orderList(PaymentPayU $payU, Security $security, PaymentRepository $paymentRepository)
    {
        $user = $security->getUser();
        $orders = $paymentRepository->getUserOrders($user->getId());
        
        return [
            'orders' => $orders,
        ];
    }

    /**
     * @Route("/cancel_order/{payment}", name="cancel_order")
     * @IsGranted("ROLE_TRAINER")
     */
    public function cancelOrder(Payment $payment, PaymentPayU $payU)
    {
        $payU->cancelPayment($payment);

        return [
        ];
    }
}
?>
