<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\User;
use App\Repository\FestivalRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Repository\VolunteerAvailabilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index(
        FestivalRepository $festivalRepository,
        TeamRepository $teamRepository,
        UserRepository $userRepository,
        VolunteerAvailabilityRepository $availabilityRepository,
        SessionInterface $session
    )
    {
        $renderData = [];

        $festivals = $festivalRepository->findAll();
        $renderData['festivals'] = $festivals;

        $currentFestival = null;
        if ($session->get('current-festival-id') != null) {
            $festival = $festivalRepository->find($session->get('current-festival-id'));
            $currentFestival = $festival;
            $renderData['currentFestival'] = $currentFestival;

            $teams = $teamRepository->findBy(['festival' => $currentFestival]);
            $renderData['teams'] = $teams;

            /** @var User $volunteers */
            $volunteers = $userRepository->findVolunteersByFestival($festival);
            $renderData['volunteers'] = $volunteers;

            dump($volunteers);

        }

        return $this->render('index/index.html.twig', $renderData);
    }

}
