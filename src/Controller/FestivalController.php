<?php

namespace App\Controller;

use App\Api\ApiClient;
use App\Api\ApiRequest;
use App\Api\Client\FestivalClient;
use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/festival")
 */
class FestivalController extends AbstractController
{
    /** @var Festival|null $currentFestival */
    private $currentFestival = null;

    public function __construct(SessionInterface $session, FestivalRepository $festivalRepository)
    {
        if ($session->get('current-festival-id') != null) {
            $festival = $festivalRepository->find($session->get('current-festival-id'));
            $this->currentFestival = $festival;
        }
    }

    /**
     * @Route("/", name="festival_index", methods={"GET"})
     * @param FestivalRepository $festivalRepository
     * @param FestivalClient $festivalClient
     * @return Response
     */
    public function index(FestivalRepository $festivalRepository, ApiRequest $apiRequest): Response
    {

        dump($apiRequest->request('http://127.0.0.1:8000/api/festivals'));
        die;

        return $this->render('festival/index.html.twig', [
            'festivals' => $festivalRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="festival_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $festival = new Festival();
        $form = $this->createForm(FestivalType::class, $festival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($festival);
            $entityManager->flush();

            return $this->redirectToRoute('festival_index');
        }

        return $this->render('festival/new.html.twig', [
            'festival' => $festival,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="festival_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(Festival $festival): Response
    {
        return $this->render('festival/show.html.twig', [
            'festival' => $festival,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="festival_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Festival $festival): Response
    {
        $form = $this->createForm(FestivalType::class, $festival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('festival_index', [
                'id' => $festival->getId(),
            ]);
        }

        return $this->render('festival/edit.html.twig', [
            'festival' => $festival,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="festival_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Festival $festival): Response
    {
        if ($this->isCsrfTokenValid('delete' . $festival->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($festival);
            $entityManager->flush();
        }

        return $this->redirectToRoute('festival_index');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/select", name="festival_select", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function select(Festival $festival, SessionInterface $session)
    {
        $session->set('current-festival-id', $festival->getId());
        return $this->redirectToRoute('app_index_index');
    }

    /**
     * @return Response
     */
    public function displayCurrentFestival()
    {
        $response = new Response();
        if ($this->currentFestival != null) {
            $response->setContent($this->currentFestival->getName());
        }
        return $response;
    }

    /**
     * @param Festival
     * @Route("/change", name="festival_change", methods={"GET"})
     */
    public function removeCurrentFestival(SessionInterface $session)
    {
        $session->clear();
        return $this->redirectToRoute("app_index_index");
    }
}
