<?php

namespace App\Entity;

use App\Repository\PathologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PathologyRepository::class)
 */
class Pathology
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("patients_get_collection")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("patients_get_collection")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("patients_get_collection")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="pathology")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    private $patients;

    /**
     * @ORM\OneToMany(targetEntity=PatientPathology::class, mappedBy="pathology")
     * 
     */
    private $patientPathologies;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
        $this->patientPathologies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Patient>
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->setPathology($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getPathology() === $this) {
                $patient->setPathology(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PatientPathology>
     */
    public function getPatientPathologies(): Collection
    {
        return $this->patientPathologies;
    }

    public function addPatientPathology(PatientPathology $patientPathology): self
    {
        if (!$this->patientPathologies->contains($patientPathology)) {
            $this->patientPathologies[] = $patientPathology;
            $patientPathology->setPathology($this);
        }

        return $this;
    }

    public function removePatientPathology(PatientPathology $patientPathology): self
    {
        if ($this->patientPathologies->removeElement($patientPathology)) {
            // set the owning side to null (unless already changed)
            if ($patientPathology->getPathology() === $this) {
                $patientPathology->setPathology(null);
            }
        }

        return $this;
    }
}
