<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Patient;
use App\Repository\UserRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


/**
 * @Route("/api")
 */
class UserControllerAPI extends AbstractController
{


    /**
     * Liste de tout les user
     * @Route("/user", name="api_app_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * Rechercher un utilisateur selon son id
     * @Route("/user/{id}", name="api_app_user_show", methods={"GET"})
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
     * @Route("/user/create", name="api_app_user_new", methods={"POST"})
     */
    public function new(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Request $request, UserRepository $userRepository): Response
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

            $user->setPassword($hashedPassword);

            // Persister et flusher l'entité User
            $entityManager->persist($user);
            $entityManager->flush();

            // Créer l'entité Patient et la lier à l'entité User
            $patient = new Patient();
            $patient->setUser($user);
            $patient->setName($user->getEmail());
            $patient->setFirstname('blabla'); 
            
            

            // Persister et flusher l'entité Patient
            $entityManager->persist($patient);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index');
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    /**
     * Modifier un utilisateur selon son id 
     * @Route("/user/{id}/edit", name="api_app_user_edit", methods={"GET", "POST"})
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
     * Supprimer un utilisateur selon son id
     * @Route("user/{id}", name="api_app_user_delete", methods={"POST"})
     */
    public function delete(UserPasswordHasherInterface $passwordHasher, Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            if (!$passwordHasher->isPasswordValid($user)) {
                throw new AccessDeniedHttpException();
                $userRepository->remove($user, true);
            }
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/usersconnect", name="api_usersconnect_item", methods={"POST", "GET"}) 
     */
    public function getCurrentUser(
        PatientRepository $patientRepository,
        JWTTokenManagerInterface $jwtManager,
        Security $security 
    ): JsonResponse
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

        $userId = $user->getId();
        $repoPatientData = $patientRepository->findOneBy(['id' => $userId]);

        $symptoms = $repoPatientData->getSymptom()->toArray();
        $treatments = $repoPatientData->getTreatments()->toArray();

        $patientData = [
                'symptoms' => $symptoms,
                'treatments' => $treatments,
            ];

        return $this->json(
            [
                'user' => $user,
                'patientData' => $patientData,
                'token' => $token
            ],
            Response::HTTP_OK,
            [],
            ["user_get_collection", "patients_get_collection"]
        );
    }

}