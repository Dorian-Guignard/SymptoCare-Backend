<?php

namespace App\Entity;

use App\Repository\SymptomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SymptomRepository::class)
 */
class Symptom
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
     * @ORM\Column(type="date")
     * @Groups("patients_get_collection")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     * @Groups("patients_get_collection")
     */
    private $time;

    /**
     * @ORM\ManyToMany(targetEntity=Patient::class, mappedBy="symptom")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    private $patients;



    public function __construct()
    {
        $this->patients = new ArrayCollection();
        
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

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
            $patient->addSymptom($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            $patient->removeSymptom($this);
        }

        return $this;
    }


}
