<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $chatId;

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private ?string $stage = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $step;

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private ?string $surname = null;

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private ?string $city = null;

    /**
     * @ORM\Column(type="integer", nullable = true)
     */
    private ?int $age = null;

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private ?string $gender = null;

    /**
     * @ORM\Column(type="boolean", nullable = true)
     */
    private ?bool $isAuth = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private ?DateTime $updatedAt;



    public function __construct(int $chatId)
    {
        $this->chatId = $chatId;
        $this->step = 0;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime("now");
    }

    public function setStage(string $name)
    {
        $this->stage = $name;
    }

    public function setStep(int $id)
    {
        $this->step = $id;
    }

    public function setAge(int $age)
    {
        $this->age = $age;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function getStage(): ?string
    {
        return $this->stage;
    }

    public function getStep(): int
    {
        return $this->step;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setGender(string $gender)
    {
        $this->gender = $gender;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setCity(string $city)
    {
        $this->city = $city;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setIsAuth(bool $isAuth)
    {
        $this->isAuth = $isAuth;
    }

    public function getIsAuth(): ?bool
    {
        return $this->isAuth;
    }

    public function resetStage()
    {
        $this->stage = null;
        $this->step = 0;
    }
}
