<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="integer")
     */
    private $id_sender;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_recipient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email_sender;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_checked;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSender(): ?int
    {
        return $this->id_sender;
    }

    public function setIdSender(int $id_sender): self
    {
        $this->id_sender = $id_sender;

        return $this;
    }

    public function getIdRecipient(): ?int
    {
        return $this->id_recipient;
    }

    public function setIdRecipient(int $id_recipient): self
    {
        $this->id_recipient = $id_recipient;

        return $this;
    }

    public function getEmailSender(): ?string
    {
        return $this->email_sender;
    }

    public function setEmailSender(string $email_sender): self
    {
        $this->email_sender = $email_sender;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getIsChecked(): ?bool
    {
        return $this->is_checked;
    }

    public function setIsChecked(bool $is_checked): self
    {
        $this->is_checked = $is_checked;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
