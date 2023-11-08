<?php

namespace App\Entity;

use App\Repository\AntecedentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AntecedentRepository::class)
 */
class Antecedent
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
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("patients_get_collection")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="antecedent")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     */
    private $patient;


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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
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

}
