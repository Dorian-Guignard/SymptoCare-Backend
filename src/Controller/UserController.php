<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Patient;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/crud")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        if ($user === null) {

            return $this->json(['message' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(

            ['user' => $user],

            Response::HTTP_OK,

            [],

            ['groups' => 'patients_get_collection']
        );
    }

    /**
     * Create user
     * 
     * @Route("/users/create", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            // Récupérer les données du formulaire
            $formData = $form->getData();

            // Récupérer l'entité Patient depuis l'entité User
            $patient = $user->getPatient();

            // Vérifier que l'entité Patient existe
            if ($patient) {
                // Attribuer les valeurs du formulaire à l'entité Patient
                $patient->setName($formData->getPatient()->getName())
                ->setFirstName($formData->getPatient()->getFirstName())
                ->setDateBirth($formData->getPatient()->getDateBirth());
            }
                $user->setPassword($hashedPassword);

            // Persister et flusher l'entité User
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index');
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     */
    public function delete(UserPasswordHasherInterface $passwordHasher, Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            if (!$passwordHasher->isPasswordValid($user)) {
                throw new AccessDeniedHttpException();
                $userRepository->remove($user, true);
            }
            

        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     *@Route("/api/usersconnect", name="app_api__usersconnect_item", methods={"GET"}) 
     */
    public function getCurrentUser(JWTTokenManagerInterface $jwtManager, Security $security)
    {
        $token = $security->getToken();
        $user = $security->getUser();

        if ($user) {
        $token = $jwtManager->create($user);
        }

        if (!$token) {
            return $this->json(['message' => 'token non trouvé.'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $security->getUser();

        if (!$user instanceof User) {
            return $this->json(['message' => 'user non trouvé.'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json(
            ['user' => $user,
            'token' => $token],
            Response::HTTP_OK,
            [],
            ['groups' => 'patients_get_collection']
        );
    }
}
