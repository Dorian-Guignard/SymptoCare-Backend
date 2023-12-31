<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ConstantRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=ConstantRepository::class)
 */
class Constant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("patients_get_collection")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups("patients_get_collection")
     */
    private $value;

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
     * @ORM\ManyToOne(targetEntity=ConstantType::class, inversedBy="constants")
     * 
     */
    private $constantType;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="constants")
     * 
     */
    private $patient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

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

    public function getConstantType(): ?ConstantType
    {
        return $this->constantType;
    }

    public function setConstantType(?ConstantType $constantType): self
    {
        $this->constantType = $constantType;

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
