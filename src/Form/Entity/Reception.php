<?php

namespace App\Entity;

use App\Repository\ReceptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReceptionRepository::class)
 */
class Reception
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $signalement;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class, inversedBy="receptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $message_id;

    /**
     * @ORM\ManyToOne(targetEntity=GroupGroup::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $group_group_id;


    public function __construct()
    {
        $this->personne = new ArrayCollection();
    }
 

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSignalement(): ?string
    {
        return $this->signalement;
    }

    public function setSignalement(?string $signalement): self
    {
        $this->signalement = $signalement;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getMessageId(): ?Message
    {
        return $this->message_id;
    }

    public function setMessageId(?Message $message_id): self
    {
        $this->message_id = $message_id;

        return $this;
    }

    public function getGroupGroupId(): ?GroupGroup
    {
        return $this->group_group_id;
    }

    public function setGroupGroupId(?GroupGroup $group_group_id): self
    {
        $this->group_group_id = $group_group_id;

        return $this;
    }




    }

