<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CribRepository")
 */
class Crib
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $editDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CribContent", mappedBy="crib", cascade={"persist"})
     */
    private $cribContent;


    public function __construct()
    {
        $this->cribContent = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }


    public function setTitle(string $title): self
    {
        $this->title = $title;

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


    public function getEditDate(): ?\DateTimeInterface
    {
        return $this->editDate;
    }


    public function setEditDate(\DateTimeInterface $editDate): self
    {
        $this->editDate = $editDate;

        return $this;
    }


    public function getCribContent(): array
    {
        return $this->cribContent->toArray();
    }


    public function addCribContent(CribContent $cribContent): self
    {
        if (!$this->cribContent->contains($cribContent)) {
            $this->cribContent[] = $cribContent;
            $cribContent->setCrib($this);
        }

        return $this;
    }


    public function removeCribContent(CribContent $cribContent): self
    {
        if ($this->cribContent->contains($cribContent)) {
            $this->cribContent->removeElement($cribContent);

            if ($cribContent->getCrib() === $this) {
                $cribContent->setCrib(null);
            }
        }

        return $this;
    }
}
