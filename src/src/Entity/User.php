<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
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
     * @ORM\Column(type="integer")
     */
    private int $stage;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private ?DateTime $updateAt;



    public function __construct(int $chatId)
    {
        $this->chatId = $chatId;
        $this->stage = 0;
        $this->createdAt = new DateTime();

    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
