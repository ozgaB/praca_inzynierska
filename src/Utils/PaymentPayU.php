<?php
namespace App\Utils;

use App\Entity\Security\User;
use App\Entity\Payment\Payment;
use Symfony\Component\Form\Form;
use TheSeer\Tokenizer\Exception;
use App\Repository\UserRepository;
use App\Entity\Invitation\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Subscription\SubscriptionType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SubscriptionTypeRepository;
use App\Repository\AddressAndContactRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\AddressAndContact\AddressAndContact;
use App\Repository\PaymentRepository;

class PaymentPayU
{
    private EntityManagerInterface $entityManager;
    private SubscriptionTypeRepository $subscriptionTypeRepository;
    private UserRepository $userRepository;
    private AddressAndContactRepository $addressAndContactRepository;
    private PaymentPayUConfig $paymentPayUConfig;
    private PaymentRepository $paymentRepository;
    private SubscriptionManager $subscriptionManager;
    private $request;

    private string $result = 'none';

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, UserRepository $userRepository, SubscriptionTypeRepository $subscriptionTypeRepository, AddressAndContactRepository $addressAndContactRepository, PaymentPayUConfig $paymentPayUConfig, PaymentRepository $paymentRepository, SubscriptionManager $subscriptionManager)
    {
        $this->entityManager = $entityManager;
        $this->request =  $requestStack->getCurrentRequest();
        $this->userRepository = $userRepository;
        $this->subscriptionTypeRepository = $subscriptionTypeRepository;
        $this->addressAndContactRepository = $addressAndContactRepository;
        $this->paymentPayUConfig = $paymentPayUConfig;
        $this->paymentRepository = $paymentRepository;
        $this->subscriptionManager = $subscriptionManager;
    }

    public function prepareAndExecuteOrder(Payment $payment)
    {
        $orderId = uniqid('', false);

        try {
            $payment->setIdOrder($orderId);
            $this->entityManager->persist($payment);
            $this->entityManager->flush();
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }

        $this->paymentPayUConfig->setPayUConfig();
        $user = $this->userRepository->find($payment->getIdUser());
        $subscriptionType = $this->subscriptionTypeRepository->find($payment->getIdSubscriptionType());
        $addressAndContact = $this->addressAndContactRepository->findOneBy(['idUser' => $user->getId()]);
        if(!$user instanceof User)
        {
            throw new Exception('User is not defined');
        }
        if(!$subscriptionType instanceof SubscriptionType)
        {
            throw new Exception('SubscriptionType is not defined');
        }
        if(!$addressAndContact instanceof AddressAndContact)
        {
            throw new Exception('Address is not defined');
        }

        $plnPriceInGr = ($subscriptionType->getPricePln())*100;

        $order = [];

        $order['notifyUrl'] = 'http://127.0.0.1:8000/notify_order';
        $order['continueUrl'] = 'http://127.0.0.1:8000/list_order';

        $order['customerIp'] = $_SERVER['REMOTE_ADDR'];
        $order['merchantPosId'] = \OpenPayU_Configuration::getOauthClientId() ? \OpenPayU_Configuration::getOauthClientId() : \OpenPayU_Configuration::getMerchantPosId();
        $order['description'] = $subscriptionType->getDescription();
        $order['currencyCode'] = 'PLN';
        $order['totalAmount'] = $plnPriceInGr; // Cena podawana w groszach
        $order['extOrderId'] = $orderId;

        $order['products'][0]['name'] = $subscriptionType->getName();
        $order['products'][0]['unitPrice'] = $subscriptionType->getPricePln();
        $order['products'][0]['quantity'] = 1;

        $order['buyer']['email'] = $user->getEmail();
        $order['buyer']['phone'] = $addressAndContact->getPhoneNumber();
        $order['buyer']['firstName'] = $user->getFirstName();
        $order['buyer']['lastName'] = $user->getLastName();
        $order['buyer']['language'] = 'pl';

        $response = \OpenPayU_Order::create($order);
        
        return $response;
    }

    public function getNotify()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $body = file_get_contents('php://input');
            $data = trim($body);
        
            try {
                if (!empty($data)) {
                    $result = \OpenPayU_Order::consumeNotification($data);
                }
        
                if ($result->getResponse()->order->orderId) {
        
                    /* Check if OrderId exists in Merchant Service, update Order data by OrderRetrieveRequest */
                    $order = \OpenPayU_Order::retrieve($result->getResponse()->order->orderId);
                    if($order->getStatus() == 'SUCCESS'){
                        $payment = $this->paymentRepository->findOneBy(['idOrder' => $result->getResponse()->order->orderId]);
                        try {
                            $payment->setStatus('Płatność zrealizowana pomyślnie');
                            $this->entityManager->persist($payment);
                            $this->entityManager->flush();
                        } catch(Exception $e) {
                            throw new Exception($e->getMessage());
                        }
                        $subscriptionType = $this->subscriptionTypeRepository->find($payment->getSubscriptionTypeId());
                        $lifetime = $subscriptionType->getLifeTime();
                        $user = $this->userRepository->find($payment->getIdUser());
                        $this->subscriptionManager->updateSubscriptionExpireAt($user,$lifetime);
                        header("HTTP/1.1 200 OK");
                    }
                }
            } catch (\OpenPayU_Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function cancelPayment(Payment $payment){
        try {
            $payment->setStatus('Płatność anulowana');
            $this->entityManager->persist($payment);
            $this->entityManager->flush();
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
