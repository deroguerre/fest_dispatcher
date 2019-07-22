<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\Job;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\VolunteerAvailability;
use App\Form\JobType;
use App\Repository\FestivalRepository;
use App\Repository\JobRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Repository\VolunteerAvailabilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/job")
 */
class JobController extends AbstractController
{
    /**
     * @Route("/", name="job_index", methods={"GET"})
     */
    public function index(JobRepository $jobRepository): Response
    {
        return $this->render('job/index.html.twig', [
            'jobs' => $jobRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="job_new", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        FestivalRepository $festivalRepository,
        VolunteerAvailabilityRepository $volunteerAvailabilityRepository,
        SessionInterface $session
    ): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User $user */
            $user = $job->getUser();
            /** @var Team $team */
            $team = $job->getTeam();
            /** @var Festival $festival */
            $festival = $team->getFestival();


            if (!$team->getVolunteers()->contains($user)) {
                $team->addVolunteer($user);
            }

            //add default availability for user if not exist
            if (!$volunteerAvailabilityRepository->findOneBy(['festival' => $festival, 'user' => $user])) {
                $availability = new VolunteerAvailability();
                $availability
                    ->setUser($user)
                    ->setFestival($festival);
                $entityManager->persist($availability);
            }

            $entityManager->persist($job);
            $entityManager->flush();

            return $this->redirectToRoute('job_index');
        }


        return $this->render('job/new.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/ajax_new", name="ajax_job_new", methods={"POST"}, options={"expose"=true})
     * @throws \Exception
     */
    public
    function newFromAjax(
        Request $request,
        TeamRepository $teamRepository,
        UserRepository $userRepository,
        FestivalRepository $festivalRepository,
        SessionInterface $session,
        VolunteerAvailabilityRepository $volunteerAvailabilityRepository
    ): Response
    {

        if ($request->isXmlHttpRequest()) {

            $data = $request->request->get('job');

            $team = $teamRepository->find($data['team']);
            $user = $userRepository->find($data['user']);
            $startDate = new \DateTime($data['startDate']);
            $endDate = new \DateTime($data['endDate']);
            $backgroundColor = $data['backgroundColor'];

            $job = new Job();
            $job->setTitle($data['title'])
                ->setTeam($team)
                ->setUser($user)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setBackgroundColor($backgroundColor);

            //add user to team
            if (!$team->getVolunteers()->contains($user)) {
                $team->addVolunteer($user);
            }

            //useless ???
//            if ($session->get('current-festival-id')) {
//
//                /** @var Festival $festival */
//                $festival = $festivalRepository->find($session->get('current-festival-id'));
//
//                //add default availability for user if not exist
//                if (!$volunteerAvailabilityRepository->findOneBy(['festival' => $festival, 'user' => $user])) {
//                    $availability = new VolunteerAvailability();
//                    $availability
//                        ->setUser($job->getUser())
//                        ->setFestival($festival);
//                }
//            } else {
//                return new Response('no festival selected');
//            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

            $response = new Response(json_encode(array(
                'message' => "job created",
                'id' => $job->getId()
            )));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return new Response("erreur : ce n'est pas une requete ajax", 400);
    }

    /**
     * @Route("/{id}", name="job_show", methods={"GET"})
     */
    public
    function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="job_edit", methods={"GET","POST"})
     */
    public
    function edit(Request $request, Job $job): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('job_index', [
                'id' => $job->getId(),
            ]);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajax_edit", name="ajax_job_edit", methods={"POST"}, options={"expose"=true})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public
    function editFromAjax(Request $request, JobRepository $jobRepository)
    {
        if ($request->isXmlHttpRequest()) {

            $data = $request->request->get('job');

            $start = new \DateTime($data['start']);
            $end = new \DateTime($data['end']);

            $job = $jobRepository->find($data['id']);

            $job->setStartDate($start)
                ->setEndDate($end);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $response = new Response(json_encode(array(
                'message' => "job edited"
            )));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return new Response("erreur : ce n'est pas une requete ajax", 400);
    }

    /**
     * @Route("/{id}", name="job_delete", methods={"DELETE"})
     */
    public
    function delete(Request $request, Job $job): Response
    {
        if ($this->isCsrfTokenValid('delete' . $job->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($job);
            $entityManager->flush();
        }

        return $this->redirectToRoute('job_index');
    }


    /**
     * @Route("/ajax_remove", name="ajax_job_remove", methods={"POST"}, options={"expose"=true})
     * @param Request $request
     * @param JobRepository $jobRepository
     * @return Response
     * @throws \Exception
     */
    public
    function removeFromAjax(Request $request, JobRepository $jobRepository)
    {
        if ($request->isXmlHttpRequest()) {

            $data = $request->request->get('job');

            $job = $jobRepository->find($data['id']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($job);
            $entityManager->flush();

            $response = new Response(json_encode(array(
                'message' => "job removed"
            )));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return new Response("erreur : ce n'est pas une requete ajax", 400);
    }
}
