<?php
namespace App\Utils;

use App\Entity\Payment\Payment;
use Symfony\Component\Form\Form;
use App\Entity\Invitation\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PaymentPayU
{
    private EntityManagerInterface $entityManager;
    private $request;

    private string $result = 'none';

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->request =  $requestStack->getCurrentRequest();
    }

    public function prepareAndExecuteOrder(Payment $paymentId)
    {
        $order = [];

        $order['notifyUrl'] = 'http://localhost'.dirname($_SERVER['REQUEST_URI']).'/OrderNotify.php';
        $order['continueUrl'] = 'http://localhost'.dirname($_SERVER['REQUEST_URI']).'/../../layout/success.php';

        $order['customerIp'] = '127.0.0.1';
        $order['merchantPosId'] = \OpenPayU_Configuration::getOauthClientId() ? \OpenPayU_Configuration::getOauthClientId() : \OpenPayU_Configuration::getMerchantPosId();
        $order['description'] = 'New order';
        $order['currencyCode'] = 'PLN';
        $order['totalAmount'] = 3200;
        $order['extOrderId'] = uniqid('', true);

        $order['products'][0]['name'] = 'Product1';
        $order['products'][0]['unitPrice'] = 1000;
        $order['products'][0]['quantity'] = 1;

        $order['products'][1]['name'] = 'Product2';
        $order['products'][1]['unitPrice'] = 2200;
        $order['products'][1]['quantity'] = 1;

        $order['buyer']['email'] = 'test_buyer_email@payu.com';
        $order['buyer']['phone'] = '123123123';
        $order['buyer']['firstName'] = 'Jan';
        $order['buyer']['lastName'] = 'Kowalski';
        $order['buyer']['language'] = 'en';

        return true;
    }
}
