<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\User;
use App\Entity\VolunteerAvailability;
use App\Form\PrepareEmailAvailabilitiesType;
use App\Form\VolunteerAvailabilityForUserType;
use App\Form\VolunteerAvailabilityType;
use App\Repository\UserRepository;
use App\Repository\VolunteerAvailabilityRepository;
use App\Service\EmailHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @Route("/new/{festival}/{user}", name="volunteer_availability_new", methods={"GET","POST"}, defaults={"festival"=null,"user"=null}, requirements={"festival":"\d+","user":"\d+"})
     * @ParamConverter("festival", options= {"mapping": {"festival": "id"}})
     * @ParamConverter("user", options= {"mapping": {"user": "id"}})
     * @throws \Exception
     */
    public function new(
        ?Festival $festival,
        ?User $user,
        Request $request
    ): Response
    {

        $token = random_bytes(20);
        dump($token);
//        die;

        //form with parameters
        if (($festival != null) && ($user != null)) {

            $volunteerAvailability = new VolunteerAvailability();
            $volunteerAvailability->setUser($user);
            $volunteerAvailability->setFestival($festival);
            $form = $this->createForm(VolunteerAvailabilityForUserType::class, $volunteerAvailability);
            $form->handleRequest($request);

            return $this->render('volunteer_availability/new.html.twig', [
                'form' => $form->createView(),
                'volunteer_availability' => $volunteerAvailability,
                'isVolunteer' => true,
                'user' => $user,
                'festival' => $festival
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
        \Swift_Mailer $mailer,
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

                dump($ignoredUsers);

                $userslist = $userRepository->findAllUsersExceptIds($ignoredUsers);

                dump($userslist);

                $data = $request->request->get('prepare_email_availabilities');

                /**
                 * @var int $key
                 * @var User $value
                 */
//                foreach ( $userslist as $key => $value) {
//                    /** @var \Swift_Mime_SimpleMessage $mail */
//                    $mail
//                        ->setSubject($data['title'])
//                        ->setFrom($contactEmail)
//                        ->setTo($value->getEmail())
//                        ->setBody($data['body'], 'text/html');
//                    dump($mail);
//                    $mailer->send($mail);
//                }

                $user = $userRepository->find('1');

                $success = $emailHelper->NotifyForAvailability($data['title'], $data['body'], $user);

//                $success = 1; // to test, delete it for prod
                if($success > 0) {
                    $this->addFlash('success', 'Votre email a bien été envoyé');
                } else {
                    $this->addFlash('success', "Une erreur c'est produit lors de l'envoi de l'email");
                }

                return $this->redirectToRoute("festival_show", ['id'=> $festival->getId()]);
            }else {
                $this->addFlash('error',"Le formulaire contient des erreurs");
            }
        }

        return $this->render('festival/emailAvailabilities.html.twig', [
            'form' => $prepareMailForm->createView(),
            'users' => $users
        ]);
    }
}
