<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 * @UniqueEntity(fields={"email"}, message="Il existe déja un compte avec cet email")
 * 
 */
class Patient
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
     * @ORM\Column(type="string", length=255)
     * @Groups("patients_get_collection")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups("patients_get_collection")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"patients_get_collection", "users_get_item"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("patients_get_collection")
     */
    private $date_birth;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     * @Groups("patients_get_collection")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Constant::class, mappedBy="patient")
     * @Groups("patients_get_collection")
     */
    private $constants;

    /**
     * @ORM\ManyToMany(targetEntity=Symptom::class, inversedBy="patients")
     * @Groups("patients_get_collection")
     */
    private $symptom;

    /**
     * @ORM\ManyToOne(targetEntity=Pathology::class, inversedBy="patients")
     * 
     */
    private $pathology;

    /**
     * @ORM\ManyToMany(targetEntity=Treatment::class, mappedBy="patients")
     * @Groups("patients_get_collection")
     */
    private $treatments;

    /**
     * @ORM\OneToMany(targetEntity=Antecedent::class, mappedBy="patient")
     * @Groups("patients_get_collection")
     */
    private $antecedent;

    /**
     * @ORM\ManyToMany(targetEntity=Doctor::class, inversedBy="patients")
     * @Groups("patients_get_collection")
     */
    private $doctor;

    /**
     * @ORM\ManyToMany(targetEntity=Clinic::class, inversedBy="patients")
     * @Groups("patients_get_collection")
     */
    private $clinic;

    /**
     * @ORM\OneToMany(targetEntity=PatientPathology::class, mappedBy="patient")
     * @Groups("patients_get_collection")
     */
    private $patientPathologies;


    public function __construct()
    {
        $this->constants = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';


        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

}
