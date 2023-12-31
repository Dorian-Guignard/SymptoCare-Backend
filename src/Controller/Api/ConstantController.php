<?php

namespace App\Controller\Api;

use DateTime;
use App\Entity\Patient;
use App\Entity\Constant;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class ConstantController extends AbstractController
{
    /**
     * @Route("/api/constant", name="app_api_constant")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/ConstantController.php',
        ]);
    }
    /**
     * @Route("/{id}/constantes", name="patient_constantes_creer", methods={"POST"})
     */
    public function creerConstante(Request $request, $id, EntityManagerInterface $entityManager, PatientRepository $patientRepository): Response
    {
        $patient = $patientRepository->find($id); {
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
    }

    /**
     * @Route("/constantes/{id}", name="patient_constante_modifier", methods={"PUT"})
     */
    public function modifierConstante(Request $request, EntityManagerInterface $entityManager, $id): Response
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

        $patient = $this->getUser();
        $constant = $entityManager->getRepository(Constant::class)->findOneBy(['id' => $id, 'patient' => $patient]);

        if (!$constant) {
            return $this->json(['error' => 'Constante non trouvée'], Response::HTTP_NOT_FOUND);
        }

        try {
            $entityManager->beginTransaction();

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

            // Enregistrez les modifications dans la base de données
            $entityManager->flush();

            $entityManager->commit();

            return $this->json(['message' => 'Constante modifiée avec succès'], Response::HTTP_OK);
        } catch (\Exception $e) {
            // En cas d'échec, annulez la transaction
            $entityManager->rollback();

            // Loggez l'exception ou renvoyez une réponse d'erreur appropriée
            return $this->json(['error' => 'Une erreur est survenue'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/patient/constantes/{id}", name="patient_constante_supprimer", methods={"DELETE"})
     */
    public function supprimerConstante(EntityManagerInterface $entityManager, $id): Response
    {
        $patient = $this->getUser();
        $constant = $entityManager->getRepository(Constant::class)->findOneBy(['id' => $id, 'patient' => $patient]);

        if (!$constant) {
            return $this->json(['error' => 'Constante non trouvée'], Response::HTTP_NOT_FOUND);
        }

        try {
            $entityManager->beginTransaction();

            // Supprimez la constante de la base de données
            $entityManager->remove($constant);
            $entityManager->flush();

            $entityManager->commit();

            return $this->json(['message' => 'Constante supprimée avec succès'], Response::HTTP_OK);
        } catch (\Exception $e) {
            // En cas d'échec, annulez la transaction
            $entityManager->rollback();

            // Loggez l'exception ou renvoyez une réponse d'erreur appropriée
            return $this->json(['error' => 'Une erreur est survenue'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
