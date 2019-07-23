<?php


namespace App\Service\AnswerAvailibilities\EventListener;

use App\Entity\VolunteerAvailability;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class AnswerSubscriber implements EventSubscriberInterface
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.volunteer_availibilities_workflow.completed.volunteer_answer' => 'onNewAnswer'
        ];
    }

    /**
     * @param Event $event
     */
    public function onNewAnswer(Event $event)
{


    /** @var  VolunteerAvailability $volunteerAvailability */
    $volunteerAvailability = $event->getSubject();
    $this->logger->info('Le bénévole'. $volunteerAvailability->getUser() .'a ajouté des disponibilités ');
}



}