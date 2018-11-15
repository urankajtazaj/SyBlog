<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudnentiRepository")
 */
class Studnenti
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emri;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmri(): ?string
    {
        return $this->emri;
    }

    public function setEmri(?string $emri): self
    {
        $this->emri = $emri;

        return $this;
    }
}
