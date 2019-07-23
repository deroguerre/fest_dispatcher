<?php


namespace App\Service\AnswerAvailibilities;


use App\Entity\Festival;
use App\Entity\Team;

/**
 * Class notification
 * @package App\Service\AnswerAvailibilities
 */
class notification
{
    private $mailer;

    /**
     * notification constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }


    public function sendNotificationNewFestivalbyManager(Team $team)
    {

    }

}