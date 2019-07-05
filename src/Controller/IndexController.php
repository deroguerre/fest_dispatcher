<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Repository\FestivalRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index(FestivalRepository $festivalRepository, TeamRepository $teamRepository, SessionInterface $session)
    {
        $renderData = [];

        $festivals = $festivalRepository->findAll();
        $renderData['festivals'] = $festivals;

        $currentFestival = null;
        if($session->get('current-festival-id') != null) {
            $festival = $festivalRepository->find($session->get('current-festival-id'));
            $currentFestival = $festival;
            $renderData['currentFestival'] = $currentFestival;

//            $teamRepository->findBy(['festival']);
        }

        return $this->render('index/index.html.twig', $renderData);
    }

}
