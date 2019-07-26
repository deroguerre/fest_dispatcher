<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\Token;
use App\Entity\User;
use App\Entity\VolunteerAvailability;
use App\Form\PrepareEmailAvailabilitiesType;
use App\Form\VolunteerAvailabilityForUserType;
use App\Form\VolunteerAvailabilityType;
use App\Repository\FestivalRepository;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Repository\VolunteerAvailabilityRepository;
use App\Service\EmailHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    public function indexByFestival(Festival $festival, VolunteerAvailabilityRepository $volunteerAvailabilityRepository): Response
    {

        $volunteers = $volunteerAvailabilityRepository->findAllByFestival($festival->getId());

        return $this->render('volunteer_availability/indexByFestival.html.twig', [
            'volunteer_availabilities' => $volunteers,
            'current_festival' => $festival
        ]);

    }

    /**
     * @Route("/new/{tokenValue}", name="volunteer_availability_new", methods={"GET","POST"}, defaults={"tokenValue"=null})
     * @throws \Exception
     */
    public function new(
        ?string $tokenValue,
        Request $request,
        TokenRepository $tokenRepository,
        UserRepository $userRepository,
        FestivalRepository $festivalRepository,
        EntityManagerInterface $entityManager
    ): Response
    {

        //form with parameters
        if ($tokenValue != "") {

            /**
             * Check if token exist in db
             * @var Token $tokenValue
             */
            $token = $tokenRepository->findOneBy(['value' => $tokenValue]);

            if (!is_null($token)) {

                $data = $token->getData();

                $user = $userRepository->find($data['user']);
                $festival = $festivalRepository->find($data['festival']);

                $volunteerAvailability = new VolunteerAvailability();
                $volunteerAvailability->setUser($user);
                $volunteerAvailability->setFestival($festival);

                $form = $this->createForm(VolunteerAvailabilityForUserType::class, $volunteerAvailability);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($volunteerAvailability);
                    $entityManager->remove($token);
                    $entityManager->flush();

                    $this->addFlash("success", "Merci d'avoir pris le temps de nous faire parvenir tes disponibilités");
                    return $this->redirectToRoute('app_index_index');
                }

                return $this->render('volunteer_availability/new.html.twig', [
                    'form' => $form->createView(),
                    'volunteer_availability' => $volunteerAvailability,
                    'token' => $token,
                    'user' => $user,
                    'festival' => $festival
                ]);
            } else {
                throw new Exception('Le token est invalide !');
            }
        }

        //form without parameters
        $volunteerAvailability = new VolunteerAvailability();
        $form = $this->createForm(VolunteerAvailabilityType::class, $volunteerAvailability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        if ($this->isCsrfTokenValid('delete' . $volunteerAvailability->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($volunteerAvailability);
            $entityManager->flush();
        }

        return $this->redirectToRoute('volunteer_availability_index');
    }

    /**
     * @Route("/prepare_email/festival/{id}", name="volunteer_availability_prepare_email", methods={"GET","POST"})
     * @return Response
     * préparer un email
     *
     */
    public function prepare_email(
        Festival $festival,
        UserRepository $userRepository,
        Request $request,
        EmailHelper $emailHelper
    )
    {
        $prepareMailForm = $this->createForm(PrepareEmailAvailabilitiesType::class);
        $prepareMailForm->handleRequest($request);

        $users = $userRepository->findAll();

        if ($prepareMailForm->isSubmitted()) {
            if ($prepareMailForm->isValid()) {

                $ignoredUsers = $request->request->get('volunteers');
                $ignoredUsers = array_map('intval', explode(",", $ignoredUsers));

                $userslist = $userRepository->findAllUsersExceptIds($ignoredUsers);

                $data = $request->request->get('prepare_email_availabilities');

                /**
                 * @var User $value
                 */
//                foreach ( $userslist as $user) {
//                    $success = $emailHelper->NotifyForAvailability($data['title'], $data['body'], $user);
//                    if(!$success) {
//                        throw new Exception("Une erreur c'est produit");
//                    }
//                }

                //to debug
                $user = $userRepository->findOneByEmail('admin@admin.com');
                $emailHelper->NotifyForAvailability($data['title'], $data['body'], $user);

                $this->addFlash("success","Les demandes de disponibilités sont envoyés !");

                return $this->redirectToRoute("volunteer_availability_by_festival", ['id' => $festival->getId()]);
            } else {
                $this->addFlash('error', "Le formulaire contient des erreurs");
            }
        }

        return $this->render('volunteer_availability/email.html.twig', [
            'form' => $prepareMailForm->createView(),
            'users' => $users
        ]);
    }
}
