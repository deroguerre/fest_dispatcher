<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EmailHelper
{
    protected $mailer;
    private $templating;
    private $entityManager;
    private $params;

    public function __construct(\Swift_Mailer $mailer, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->params = $params;
    }

    public function NotifyForAvailability(string $subject, $body, $to)
    {
        /** @var \Swift_Mime_SimpleMessage $mail */
        $mail = $this->mailer->createMessage();

        $mail
            ->setFrom($this->params->get('contact_email'))
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body, 'text/html');

        dump($mail);

        $success = $this->mailer->send($mail);

        return $success;
    }
}