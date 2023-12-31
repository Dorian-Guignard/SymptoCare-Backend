<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PatientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $date_birth;

    /**
     * @ORM\OneToMany(targetEntity=Constant::class, mappedBy="patient")
     * 
     * 
     */
    private $constants;

    /**
     * @ORM\ManyToMany(targetEntity=Symptom::class, inversedBy="patients")
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $symptom;

    /**
     * @ORM\ManyToOne(targetEntity=Pathology::class, inversedBy="patients")
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $pathology;

    /**
     * @ORM\ManyToMany(targetEntity=Treatment::class, mappedBy="patients")
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $treatments;

    /**
     * @ORM\OneToMany(targetEntity=Antecedent::class, mappedBy="patient")
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $antecedent;

    /**
     * @ORM\ManyToMany(targetEntity=Doctor::class, inversedBy="patients")
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $doctor;

    /**
     * @ORM\ManyToMany(targetEntity=Clinic::class, inversedBy="patients")
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $clinic;

    /**
     * @ORM\OneToMany(targetEntity=PatientPathology::class, mappedBy="patient")
     * @Groups("patients_get_collection", "user_get_collection")
     */
    private $patientPathologies;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="patient", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    public function __construct()
    {
       
        $this->symptom = new ArrayCollection();
        $this->treatments = new ArrayCollection();
        $this->antecedent = new ArrayCollection();
        $this->doctor = new ArrayCollection();
        $this->clinic = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }


    public function getDateBirth(): ?string
    {
        return $this->date_birth;
    }

    public function setDateBirth(string $date_birth): self
    {
        $this->date_birth = $date_birth;

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
            $constant->setPatient($this);
        }

        return $this;
    }

    public function removeConstant(Constant $constant): self
    {
        if ($this->constants->removeElement($constant)) {
            // set the owning side to null (unless already changed)
            if ($constant->getPatient() === $this) {
                $constant->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Symptom>
     */
    public function getSymptom(): Collection
    {
        return $this->symptom;
    }

    public function addSymptom(Symptom $symptom): self
    {
        if (!$this->symptom->contains($symptom)) {
            $this->symptom[] = $symptom;
        }

        return $this;
    }

    public function removeSymptom(Symptom $symptom): self
    {
        $this->symptom->removeElement($symptom);

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

    /**
     * @return Collection<int, Treatment>
     */
    public function getTreatments(): Collection
    {
        return $this->treatments;
    }


    /**
     * @return Collection<int, Treatment>
     */
    public function addTreatment(Treatment $treatment): self
    {
        if (!$this->treatments->contains($treatment)) {
            $this->treatments[] = $treatment;
        }

        return $this;
    }

    public function removeTreatment(Treatment $treatment): self
    {
        $this->treatments->removeElement($treatment);

        return $this;
    }

    /**
     * @return Collection<int, Antecedent>
     */
    public function getAntecedent(): Collection
    {
        return $this->antecedent;
    }

    public function addAntecedent(Antecedent $antecedent): self
    {
        if (!$this->antecedent->contains($antecedent)) {
            $this->antecedent[] = $antecedent;
            $antecedent->setPatient($this);
        }

        return $this;
    }

    public function removeAntecedent(Antecedent $antecedent): self
    {
        if ($this->antecedent->removeElement($antecedent)) {
            // set the owning side to null (unless already changed)
            if ($antecedent->getPatient() === $this) {
                $antecedent->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Doctor>
     */
    public function getDoctor(): Collection
    {
        return $this->doctor;
    }

    public function addDoctor(Doctor $doctor): self
    {
        if (!$this->doctor->contains($doctor)) {
            $this->doctor[] = $doctor;
        }

        return $this;
    }

    public function removeDoctor(Doctor $doctor): self
    {
        $this->doctor->removeElement($doctor);

        return $this;
    }

    /**
     * @return Collection<int, Clinic>
     */
    public function getClinic(): Collection
    {
        return $this->clinic;
    }

    public function addClinic(Clinic $clinic): self
    {
        if (!$this->clinic->contains($clinic)) {
            $this->clinic[] = $clinic;
        }

        return $this;
    }

    public function removeClinic(Clinic $clinic): self
    {
        $this->clinic->removeElement($clinic);

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
            $patientPathology->setPatient($this);
        }

        return $this;
    }

    public function removePatientPathology(PatientPathology $patientPathology): self
    {
        if ($this->patientPathologies->removeElement($patientPathology)) {
            // set the owning side to null (unless already changed)
            if ($patientPathology->getPatient() === $this) {
                $patientPathology->setPatient(null);
            }
        }

        return $this;
    }


    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
