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

    public function getStage()
    {
        return $this->stage;
    }

    public function getStep()
    {
        return $this->step;
    }
}