<?php

namespace App\Entity;

use App\Repository\GroupGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupGroupRepository::class)
 **/
class GroupGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     **/
    private $id;

    /**
     * @ORM\Column(type="string", length=78)
     **/
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=personne::class)
     * @ORM\JoinColumn(nullable=false)
     **/
    private $pers_init_id;

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

    public function getPersInitId(): ?personne
    {
        return $this->pers_init_id;
    }

    public function setPersInitId(?personne $pers_init_id): self
    {
        $this->pers_init_id = $pers_init_id;

        return $this;
    }
}
