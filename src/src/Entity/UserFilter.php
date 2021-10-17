<?php

namespace App\Entity;

use App\Repository\UserFilterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserFilterRepository::class)
 */
class UserFilter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ageFrom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ageTo;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $gender;

    /**
     * @ORM\OneToOne(targetEntity="User", mappedBy="userFilter")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgeFrom(): ?int
    {
        return $this->ageFrom;
    }

    public function setAgeFrom(?int $ageFrom): self
    {
        $this->ageFrom = $ageFrom;

        return $this;
    }

    public function getAgeTo(): ?int
    {
        return $this->ageTo;
    }

    public function setAgeTo(?int $ageTo): self
    {
        $this->ageTo = $ageTo;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
