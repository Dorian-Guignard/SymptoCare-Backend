<?php

namespace App\Entity;

use App\Repository\PatientPathologyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PatientPathologyRepository::class)
 */
class PatientPathology
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="patientPathologies")
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity=Pathology::class, inversedBy="patientPathologies")
     * 
     * @Groups("patients_get_collection")
     */
    private $pathology;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getPathology(): ?Pathology
    {
        return $this->pathology;
    }

    public function setPathology(?Pathology $pathology): self
    {
        $this->pathology = $pathology;

        return $this;
    }
}
