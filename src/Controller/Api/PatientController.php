<?php

namespace App\Controller\Api;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

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
 * @Route("/patient/{id}/constantes", name="patient_constantes_creer", methods={"POST"})
 */
public function creerConstante(Request $request, $id, EntityManagerInterface $entityManager): Response
{
    $patient = $this->getDoctrine()->getRepository(Patient::class)->find($id);
{
    $jsonContent = $request->getContent();
    $requestData = json_decode($jsonContent, true);

    // Vérifiez si les données nécessaires sont présentes et ont les types attendus
    if (
        !isset($requestData['value'], $requestData['date'], $requestData['time'], $requestData['constantType']) ||
        !is_numeric($requestData['value']) ||
        !DateTime::createFromFormat('Y-m-d', $requestData['date']) ||
        !DateTime::createFromFormat('H:i:s', $requestData['time'])
    ) {
        return $this->json(['error' => 'Données invalides ou manquantes'], Response::HTTP_BAD_REQUEST);
    }

    // Supposons que l'utilisateur connecté est un patient
    $patient = $this->getUser();

    try {
        $entityManager->beginTransaction();

        $constant = new Constant();
        $constant->setValue($requestData['value']);
        $constant->setDate(new \DateTime($requestData['date']));
        $constant->setTime(new \DateTime($requestData['time']));

        // Vérifiez si le type de constante existe dans la base de données
        $constantTypeId = $requestData['constantType'];
        $constantType = $entityManager->getRepository(ConstantType::class)->find($constantTypeId);

        if (!$constantType) {
            return $this->json(['error' => 'Type de constante invalide'], Response::HTTP_BAD_REQUEST);
        }

        $constant->setConstantType($constantType);
        $constant->setPatient($patient);

        // Enregistrez la constante dans la base de données
        $entityManager->persist($constant);
        $entityManager->flush();

        $entityManager->commit();

        return $this->json(['message' => 'Constante ajoutée avec succès'], Response::HTTP_CREATED);
    } catch (\Exception $e) {
        // En cas d'échec, annulez la transaction
        $entityManager->rollback();

        // Loggez l'exception ou renvoyez une réponse d'erreur appropriée
        return $this->json(['error' => 'Une erreur est survenue'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
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

