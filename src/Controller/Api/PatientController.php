<?php

namespace App\Controller\Api;


use App\Entity\Patient;
use App\Repository\PatientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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

    
    
}

