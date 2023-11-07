<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
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
    public function list(): JsonResponse
    {
        // Code pour récupérer la liste des patients depuis la base de données
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        // Code pour afficher un patient spécifique par son ID
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        // Code pour créer un nouveau patient en fonction des données de la requête
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     */
    public function update(int $id, Request $request): JsonResponse
    {
        // Code pour mettre à jour un patient existant en fonction des données de la requête
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        // Code pour supprimer un patient par son ID
    }
}

