<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\User;
use App\Entity\VolunteerAvailability;
use App\Form\VolunteerAvailabilityType;
use App\Repository\VolunteerAvailabilityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/volunteer/availability")
 */
class VolunteerAvailabilityController extends AbstractController
{
    /**
     * @Route("/", name="volunteer_availability_index", methods={"GET"})
     */
    public function index(VolunteerAvailabilityRepository $volunteerAvailabilityRepository): Response
    {
        return $this->render('volunteer_availability/index.html.twig', [
            'volunteer_availabilities' => $volunteerAvailabilityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/festival/{id}", name="volunteer_availability_by_festival")
     */
    public function indexByFestival(Festival $festival, VolunteerAvailabilityRepository $volunteerAvailabilityRepository): Response {

        $volunteers = $volunteerAvailabilityRepository->findAllByFestival($festival->getId());

        return $this->render('volunteer_availability/indexByFestival.html.twig', [
            'volunteer_availabilities' => $volunteers,
            'current_festival' => $festival
        ]);

    }

    /**
     * @Route("/new/{festival}/{user}", name="volunteer_availability_new", methods={"GET","POST"}, defaults={"festival"=null,"user"=null})
     * @ParamConverter("festival", options= {"mapping": {"festival": "id"}})
     * @ParamConverter("user", options= {"mapping": {"user": "id"}})
     */
    public function new(
        ?Festival $festival,
        ?User $user,
        Request $request
    ): Response
    {

        //form with parameters
        if( ($festival != null) && ($user != null)) {

            $volunteerAvailability = new VolunteerAvailability();
            $volunteerAvailability->setUser($user);
            $volunteerAvailability->setFestival($festival);
            $form = $this->createForm(VolunteerAvailabilityType::class, $volunteerAvailability);
            $form->handleRequest($request);

            return $this->render('volunteer_availability/new.html.twig', [
                'volunteer_availability' => $volunteerAvailability,
                'form' => $form->createView(),
            ]);
        }

        //form without parameters
        $volunteerAvailability = new VolunteerAvailability();
        $form = $this->createForm(VolunteerAvailabilityType::class, $volunteerAvailability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($volunteerAvailability);
            $entityManager->flush();

            return $this->redirectToRoute('volunteer_availability_index');
        }

        return $this->render('volunteer_availability/new.html.twig', [
            'volunteer_availability' => $volunteerAvailability,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="volunteer_availability_show", methods={"GET"})
     */
    public function show(VolunteerAvailability $volunteerAvailability): Response
    {
        return $this->render('volunteer_availability/show.html.twig', [
            'volunteer_availability' => $volunteerAvailability,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="volunteer_availability_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, VolunteerAvailability $volunteerAvailability): Response
    {
        $form = $this->createForm(VolunteerAvailabilityType::class, $volunteerAvailability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('volunteer_availability_index', [
                'id' => $volunteerAvailability->getId(),
            ]);
        }

        return $this->render('volunteer_availability/edit.html.twig', [
            'volunteer_availability' => $volunteerAvailability,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="volunteer_availability_delete", methods={"DELETE"})
     */
    public function delete(Request $request, VolunteerAvailability $volunteerAvailability): Response
    {
        if ($this->isCsrfTokenValid('delete'.$volunteerAvailability->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($volunteerAvailability);
            $entityManager->flush();
        }

        return $this->redirectToRoute('volunteer_availability_index');
    }
}
