<?php


namespace App\Service;


use App\Repository\FestivalRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Base
{

    /**
     * @return Response
     */
    public function displayCurrentFestival(SessionInterface $session, FestivalRepository $festivalRepository)
    {
        $response = new Response();

        $currentFestivalId = $session->get('current-festival-id');
        dump($currentFestivalId);
//        die;

        if($currentFestivalId != null) {

            $festival = $festivalRepository->find($currentFestivalId);
            $response->setContent($festival->getName());
        }

        return $response;
    }

}