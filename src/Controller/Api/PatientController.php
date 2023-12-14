<?php

namespace App\Controller\Api;


use App\Entity\User;
use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


/**
 * @Route("/api/patients", name="api_patient_")
 */
class PatientController extends AbstractController
{

    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function list(PatientRepository $patientRepository): JsonResponse
    {
        $patients = $patientRepository->findAll();
        if ($patients === null) {
            return $this->json(['message' => 'patient non trouvés.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            ['patients' => $patients],
            Response::HTTP_OK,
            [],
            ['groups' => 'patients_get_collection']
        );
    }


    /**
     * @Route("/{id<\d+>}", name="show", methods={"GET"})
     */
    public function show(Patient $patient, PatientRepository $patientRepository): JsonResponse
    {
        $id = $patient->getId();
        $patients = $patientRepository->find($id);
        if ($patients === null) {
            return $this->json(['message' => 'patient non trouvés.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json(
            ['patients' => $patients],
            Response::HTTP_OK,
            [],
            ['groups' => 'patients_get_collection']
        );

    }

    /**
     * Create patient
     * 
     * @Route("/", name="app_api_post_patients_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {

        $data = $request->request->all();
        
        $user = $serializer->deserialize(json_encode($data), Patient::class, "json");


        // Hash the password
        $plainPassword = $user->getPassword();

        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);

        $user->setPassword($hashedPassword);

        $errors = $validator->validate($user);

        $errorsList = [];
        if (count($errors) > 0) {

            foreach ($errors as $error) {

                $errorsList[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json($errorsList, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->persist($user);

        $entityManager->flush();


        return $this->json(

            ['patient' => $user],

            Response::HTTP_CREATED,

            [
                'Location' => $this->generateUrl(
                    'show',
                    ['id' => $user->getId()]
                )
            ],

            ['groups' => 'patients_get_collection']
        );
    }
    /**
     * Get patient data for the current user
     *
     * @Route("/currentuserbypatient", name="current_user_data", methods={"GET"})
     */
    public function getCurrentUserPatientData(): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->json(['message' => 'Utilisateur non trouvé.'], Response::HTTP_UNAUTHORIZED);
        }

        // Récupérer le patient associé à l'utilisateur
        $patient = $user->getPatient();

        if (!$patient instanceof Patient) {
            return $this->json(['message' => 'Patient non trouvé pour cet utilisateur.'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer les données associées au patient
        $patientData = [
            'constants' => $patient->getConstants(),
            'symptoms' => $patient->getSymptom(),
            'treatments' => $patient->getTreatments(),
            // Ajoutez d'autres données si nécessaire
        ];

        return $this->json(
            [
                'user' => $user,
                'patient' => $patientData,
            ],
            Response::HTTP_OK,
            [],
            ['groups' => 'user_get_collection', 'patients_get_collection']
        );
    }
        

}

