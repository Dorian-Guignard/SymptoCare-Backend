<?php

namespace App\Entity;

use App\Repository\ConstantTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConstantTypeRepository::class)
 */
class ConstantType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $normalRange;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $unit;

    /**
     * @ORM\OneToMany(targetEntity=Constant::class, mappedBy="constantType")
     */
    private $constants;

    public function __construct()
    {
        $this->constants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNormalRange(): ?string
    {
        return $this->normalRange;
    }

    public function setNormalRange(string $normalRange): self
    {
        $this->normalRange = $normalRange;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, Constant>
     */
    public function getConstants(): Collection
    {
        return $this->constants;
    }

    public function addConstant(Constant $constant): self
    {
        if (!$this->constants->contains($constant)) {
            $this->constants[] = $constant;
            $constant->setConstantType($this);
        }

        return $this;
    }

    public function removeConstant(Constant $constant): self
    {
        if ($this->constants->removeElement($constant)) {
            // set the owning side to null (unless already changed)
            if ($constant->getConstantType() === $this) {
                $constant->setConstantType(null);
            }
        }

        return $this;
    }
}
