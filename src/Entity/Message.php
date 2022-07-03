<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column (nullable=true, type="string")
     */
    private $mimeType;

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     */
    public function setMimeType($mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @var UploadedFile
     * @Assert\File(mimeTypes= {"image/jpeg","image/jpg","image/png"}, maxSize="20000000" )
     */
    private $file;

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }
    /**
     * @ORM\ManyToOne(targetEntity=Personne::class, inversedBy="messages",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $expediteur;

    /**
     * @ORM\Column(type="text")
     */
    private $message_txt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;


    /**
     * @ORM\ManyToOne(targetEntity=GroupGroup::class,cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $group_group_id;

    /**
     * @ORM\ManyToOne(targetEntity=Message::class)
     */
    private $message_ref;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpediteur(): ?Personne
    {
        return $this->expediteur;
    }

    public function setExpediteur(?Personne $expediteur): self
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getMessageTxt(): ?string
    {
        return $this->message_txt;
    }

    public function setMessageTxt(string $message_txt): self
    {
        $this->message_txt = $message_txt;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

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

    public function getGroupGroupId(): ?GroupGroup
    {
        return $this->group_group_id;
    }

    public function setGroupGroupId(?GroupGroup $group_group_id): self
    {
        $this->group_group_id = $group_group_id;

        return $this;
    }

    public function getMessageRef(): ?self
    {
        return $this->message_ref;
    }

    public function setMessageRef(?self $message_ref): self
    {
        $this->message_ref = $message_ref;

        return $this;
    }

  
}
