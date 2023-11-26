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
 * @Route("/api/user")
 */
class UserControllerAPI extends AbstractController
{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @Route("/", name="api_app_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="api_app_user_show", methods={"GET"})
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
     * @Route("/create", name="api_app_user_new", methods={"POST"})
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
            $patient->setName($user->getEmail()); // Utilisez le nom de l'utilisateur comme exemple, ajustez selon vos besoins
            $patient->setFirstname('blabla'); // Ajoutez d'autres propriétés si nécessaire
            
            // Définissez d'autres propriétés si nécessaire

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
     * @Route("/{id}/edit", name="api_app_user_edit", methods={"GET", "POST"})
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
     * @Route("/{id}", name="api_app_user_delete", methods={"POST"})
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
            [
                'user' => $user,
                'token' => $token
            ],
            Response::HTTP_OK,
            [],
            ['groups' => 'patients_get_collection']
        );
    }
}