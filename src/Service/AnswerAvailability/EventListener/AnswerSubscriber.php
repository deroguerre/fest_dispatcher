<?php


namespace App\Service\AnswerAvailability\EventListener;

use App\Entity\VolunteerAvailability;
use App\Service\AnswerAvailability\AnswerNotification;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class AnswerSubscriber implements EventSubscriberInterface
{
    /** @var LoggerInterface */
    private $logger;
    private $AnswerNotification;

    public function __construct(LoggerInterface $logger, AnswerNotification $answerNotification)
    {
        $this->logger = $logger;
        $this->AnswerNotification = $answerNotification;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.volunteer_availabilities_workflow.completed.volunteer_answer' => 'onNewAnswer'
        ];
    }

    /**
     * @param Event $event
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onNewAnswer(Event $event)
    {
        /** @var  VolunteerAvailability $volunteerAvailability */
        $volunteerAvailability = $event->getSubject();

        #save in log
        $this->logger->info('The volunteer' . $volunteerAvailability->getUser() . 'had new availabilities');

        #email notification for admin
        $this->AnswerNotification->sendNewAnswersNotificationAvailability($volunteerAvailability);
    }


}