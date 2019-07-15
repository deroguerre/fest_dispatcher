<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\User;
use App\Form\FestivalType;
use App\Form\PrepareEmailAvailabilitiesType;
use App\Repository\FestivalRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\TokenParser\SetTokenParser;

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
     * @return Response
     */
    public function index(FestivalRepository $festivalRepository): Response
    {

        return $this->render('festival/index.html.twig', [
            'festivals' => $festivalRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="festival_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
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
     * @param Festival $festival
     * @return Response
     */
    public function show(Festival $festival): Response
    {
        return $this->render('festival/show.html.twig', [
            'festival' => $festival,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="festival_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Festival $festival
     * @return Response
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
     * @param Request $request
     * @param Festival $festival
     * @return Response
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
     * @Route("/{id}/select", name="festival_select", methods={"GET"}, requirements={"id":"\d+"})
     * @param Festival $festival
     * @param SessionInterface $session
     * @return RedirectResponse
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
     * @Route("/change", name="festival_change", methods={"GET"})
     * @param SessionInterface $session
     * @return RedirectResponse
     */
    public function removeCurrentFestival(SessionInterface $session)
    {
        $session->clear();
        return $this->redirectToRoute("app_index_index");
    }

    /**
     * @Route("/{id}/email", name="festival_email", methods={"GET","POST"})
     * @return Response
     * préparer un email
     *
     */
    public function email(
        Festival $festival,
        UserRepository $userRepository,
        Request $request,
        Session $session,
        \Swift_Mailer $mailer
    )
    {
        $prepareMailForm = $this->createForm(PrepareEmailAvailabilitiesType::class);
        $prepareMailForm->handleRequest($request);
        $users = $userRepository->findAll();

        if ($prepareMailForm->isSubmitted()) {
            if ($prepareMailForm->isValid()) {

                $ignoredUsers = $request->request->get('volunteers');
//                $ignoredUsers = explode(",", $ignoredUsers);
                $ignoredUsers = array_map('intval', explode(",", $ignoredUsers));

                dump($ignoredUsers);

                $userslist = $userRepository->findAllUsersExceptIds($ignoredUsers);

                dump($userslist);

                $contactEmail = $this->getParameter('contact_email');
                $mail=$mailer->createMessage();
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


                //test email
                /** @var \Swift_Mime_SimpleMessage $mail */
                $mail
                        ->setSubject($data['title'])
                        ->setFrom($contactEmail)
                        ->setTo("deroguerre@gmail.com")
                        ->setBody($data['body'], 'text/html');
                    dump($mail);
                    $mailer->send($mail);



                $this->addFlash('success', 'Votre email a bien été envoyé');
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
