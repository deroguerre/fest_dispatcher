<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\User;
use App\Repository\FestivalRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Repository\VolunteerAvailabilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        SessionInterface $session,
        Request $request
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

        }

//        $request->setLocale('en');
        dump($request->getLocale());

        return $this->render('index/index.html.twig', $renderData);
    }

    /**
     * @param string $locale
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/changeLocale/{locale}")
     */
    public function changeLocale(string $locale, Request $request, SessionInterface $session)
    {

        $request->setLocale($locale);
        $request->setDefaultLocale($locale);
        $session->set('_locale', $locale);

        return $this->redirectToRoute('app_index_index');
    }

}
