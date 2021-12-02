<?php
namespace App\Controller\NotificationController;

use App\Repository\InvitationRepository;
use App\Repository\ParticipantRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Utils\SubscriptionManager;

class NotificationController extends AbstractController {

    public function renderInvitationNotificationForTrainer(InvitationRepository $invitationRepository, Security $security)
    {
        $trainerId = $security->getUser()->getId();
        $notification = $invitationRepository->getCountOfActualTrainerInvitation($trainerId);
        if('0' === $notification){
            $notification = null;
        }
        
        return $this->render(
            'notification/info_notification.html.twig',
            [
                'notificationValue' => $notification,
                'notificationName' => 'Nowe zaproszenia',
            ]
            );
    }

    public function renderActiveParticipantsNotificationForTrainer(ParticipantRepository $participantRepository, Security $security)
    {
        $trainerId = $security->getUser()->getId();
        $notification = $participantRepository->getCountOfActualTrainerParticipants($trainerId);

        return $this->render(
            'notification/info_notification.html.twig',
            [
                'notificationValue' => $notification,
                'notificationName' => 'Aktualni uczestnicy',
            ]
            );
    }

    public function renderSubscriptionIsNearlyExpireNotificationForTrainer(ParticipantRepository $participantRepository, Security $security, SubscriptionManager $subscriptionManager)
    {
        $status = $subscriptionManager->isSubscriptionExpireIn3Days($security->getUser());

        return $this->render(
            'notification/subscription_expire_notification.html.twig',
            [
                'status' => $status,
                'notificationName' => 'Uwaga! Twoja subskrypcja wygaśnie w najbliższych dniach',
            ]
            );
    }
}


?>