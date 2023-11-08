<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManager;
use App\Repository\PatientRepository;
use App\Repository\AntecedentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
    public function list(PatientRepository $patientRepository, AntecedentRepository $antecedentRepository): JsonResponse
    {
        $patients = $patientRepository->findAll();
        

        return $this->json(
            ['patients' => $patients],
            Response::HTTP_OK,
            [],
            ['groups' => 'patients_get_collection']
        );
    }


    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(int $id)
    {
        // Code pour afficher un patient spécifique par son ID
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function create(Request $request)
    {
        // Code pour créer un nouveau patient en fonction des données de la requête
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     */
    public function update(int $id, Request $request)
    {
        // Code pour mettre à jour un patient existant en fonction des données de la requête
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(int $id)
    {
        // Code pour supprimer un patient par son ID
    }
}

