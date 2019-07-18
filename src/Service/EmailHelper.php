<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class EmailHelper
{
    private $router;
    private $params;
    private $session;
    protected $mailer;
    private $templating;
    private $entityManager;

    public function __construct(RouterInterface $router, ParameterBagInterface $params, SessionInterface $session, \Swift_Mailer $mailer)
    {
        $this->router = $router;
        $this->params = $params;
        $this->session = $session;
        $this->mailer = $mailer;
    }

    public function NotifyForAvailability(string $subject, string $body, User $user)
    {
        /** @var \Swift_Mime_SimpleMessage $mail */
        $mail = $this->mailer->createMessage();



        $customAvailabilityUrl = $this->router->generate('volunteer_availability_new', array(
            'festival' => $this->session->get('current-festival-id'),
            'user' => $user->getId()
        ), UrlGeneratorInterface::ABSOLUTE_URL);

        $body .= "<br>Saisir ses disponibilités : $customAvailabilityUrl";



        $mail
//            ->setFrom($this->params->get('contact_email'))
            ->setFrom("no-reply@sandboxc2c8910fa65c4da0a4d787447df36060.mailgun.org")
            ->setTo("deroguerre@gmail.com")
            ->setSubject($subject)
            ->setBody($body, 'text/html');
        dump($mail);

        $success = $this->mailer->send($mail);

        return $success;
    }
}