<?php


namespace App\Service\AnswerAvailibilities\EventListener;


use App\Entity\VolunteerAvailability;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;


/**
 * @property LoggerInterface logger
 */
class AnswerSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.volunteer_availibilities_workflow.completed.draft_email' => 'onNewAnswer'
        ];
    }

    /**
     * created email
     * @param Event $event
     */
    public function onNewAnswer(Event $event)
    {
        /**
         * @var VolunteerAvailability $emailalerte
         */
        $emailalerte = $event->getSubject();

        #save in log
        $this->logger->info('nouvel email pour le festival' . $emailalerte->getFestival()->getName());
    }
    #notication au manager par email




}