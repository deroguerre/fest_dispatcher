<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\Job;
use App\Entity\Team;
use App\Entity\User;
use App\Repository\FestivalRepository;
use App\Repository\JobRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Repository\VolunteerAvailabilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


class IndexController extends AbstractController
{


    /**
     * @Route("/")
     */
    public function index(
        FestivalRepository $festivalRepository,
        TeamRepository $teamRepository,
        UserRepository $userRepository,
        JobRepository $jobRepository,
        SessionInterface $session,
        SerializerInterface $serializer,
        Request $request
    )
    {
        $rendererData = [];

        $festivals = $festivalRepository->findAll();
        $rendererData['festivals'] = $festivals;

        $currentFestival = null;
        if ($session->get('current-festival-id') != null) {

            /** @var Festival $festival */
            $festival = $festivalRepository->find($session->get('current-festival-id'));
            $currentFestival = $festival;
            $rendererData['currentFestival'] = $currentFestival;

            /** @var Team $teams */
            $teams = $teamRepository->findBy(['festival' => $currentFestival]);
            $rendererData['teams'] = $teams;

            /** @var User $volunteers */
            $volunteers = $userRepository->findVolunteersByFestival($festival);
            $rendererData['volunteers'] = $volunteers;

            /** @var Job $jobs */
            $jobs = $jobRepository->findByFestival($festival);
            $rendererData['jobs'] = $serializer->serialize($jobs, 'json');

//            dump($rendererData['jobs']);
//            die;

        }

//        $request->setLocale('en');
        dump($request->getLocale());

        return $this->render('index/index.html.twig', $rendererData);
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
