<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController
{

    private $currentFestival = null;

    public function __construct(SessionInterface $session, FestivalRepository $festivalRepository)
    {
        if($session->get('selected-festival-id') != null) {
            $festival = $festivalRepository->find($session->get('selected-festival-id'));
            $this->currentFestival = $festival;
        }
    }

    /**
     * @Route("/")
     */
    public function index(FestivalRepository $festivalRepository, SessionInterface $session)
    {
        $festivals = $festivalRepository->findAll();

        $session->clear();

        return $this->render('index/index.html.twig', [
            'festivals' => $festivals,
            'currentFestival' => $this->currentFestival
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/select-festival/{id}")
     */
    public function selectFestival(Festival $festival, SessionInterface $session)
    {
        $session->set('selected-festival-id', $festival->getId());
        return $this->redirectToRoute('app_index_index');
    }

    public function currentFestival() {

        $response = new Response();
        if($this->currentFestival != null) {
            $response->setContent($this->currentFestival->getName());
        }
        return $response;
    }
}
