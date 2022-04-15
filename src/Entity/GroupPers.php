<?php

namespace App\Entity;

use App\Repository\GroupPersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupPersRepository::class)
 */
class GroupPers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=personne::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $personne_id;

    /**
     * @ORM\ManyToOne(targetEntity=GroupGroup::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $group_group_id;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPersonneId(): ?personne
    {
        return $this->personne_id;
    }

    public function setPersonneId(?personne $personne_id): self
    {
        $this->personne_id = $personne_id;

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
