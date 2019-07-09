<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Repository\JobRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function new(Request $request): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
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
     * @Route("/ajax_edit", name="ajax_job_edit", methods={"POST"}, options={"expose"=true})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function  editFromAjax(Request $request, JobRepository $jobRepository) {
        if($request->isXmlHttpRequest()) {

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
     * @param Request $request
     * @return Response
     * @Route("/ajax_new", name="ajax_job_new", methods={"POST"}, options={"expose"=true})
     * @throws \Exception
     */
    public function newFromAjax(Request $request, TeamRepository $teamRepository, UserRepository $userRepository): Response
    {

        if ($request->isXmlHttpRequest()) {

            $data = $request->request->get('job');

            $team = $teamRepository->find($data['team']);
            $user = $userRepository->find($data['user']);
            $startDate = new \DateTime($data['startDate']);
            $endDate = new \DateTime($data['endDate']);

            $job = new Job();
            $job->setTitle($data['title'])
                ->setTeam($team)
                ->setUser($user)
                ->setStartDate($startDate)
                ->setEndDate($endDate);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

            $response = new Response(json_encode(array(
              'message' => "job created"
            )));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return new Response("erreur : ce n'est pas une requete ajax", 400);
    }

    /**
     * @Route("/{id}", name="job_show", methods={"GET"})
     */
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="job_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Job $job): Response
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
     * @Route("/{id}", name="job_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Job $job): Response
    {
        if ($this->isCsrfTokenValid('delete' . $job->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($job);
            $entityManager->flush();
        }

        return $this->redirectToRoute('job_index');
    }
}
