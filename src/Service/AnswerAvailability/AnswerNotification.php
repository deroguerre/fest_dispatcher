<?php


namespace App\Service\AnswerAvailability;


use App\Entity\VolunteerAvailability;
use Twig\Environment;

class AnswerNotification
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    private $twig;

    /**
     * AnswerNotification constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param VolunteerAvailability $volunteerAvailability
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendNewAnswersNotificationAvailability(VolunteerAvailability $volunteerAvailability)
    {
        $message = (new \Swift_Message('New Availability'))
            ->setFrom('contact@festdispatcher.fr')
            ->setTo('admin@admin.com')
            ->setBody(
                $this->twig->render(
                    'volunteer_Availability/Notification.html.twig',
                    [
                        'festival' => $volunteerAvailability->getFestival(),
                        'volunteerName' => $volunteerAvailability->getUser()->getFirstname(),
                        'startDate' => $volunteerAvailability->getStartDate(),
                        'endDate' => $volunteerAvailability->getEndDate(),
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}