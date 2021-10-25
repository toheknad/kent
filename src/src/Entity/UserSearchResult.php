<?php

namespace App\Entity;

use App\Repository\UserSearchResultRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserSearchResultRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class UserSearchResult
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userSearchResult")
     */
    private $userFrom;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userSearchResult")
     */
    private $userTo;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private ?DateTime $updatedAt;

    public function __construct()
    {
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserTo(): User
    {
        return $this->userTo;
    }

    public function setUserTo(User $userTo): self
    {
        $this->userTo = $userTo;

        return $this;
    }

    public function getUserFrom(): User
    {
        return $this->userFrom;
    }

    public function setUserFrom(User $userFrom): self
    {
        $this->userFrom = $userFrom;

        return $this;
    }
}
