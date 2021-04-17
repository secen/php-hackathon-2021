<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Programmes
 *
 * @ORM\Table(name="programmes", indexes={@ORM\Index(name="RoomID", columns={"RoomID"})})
 * @ORM\Entity
 */
class Programmes
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="MaxParticipans", type="integer", nullable=false)
     */
    private $maxparticipans;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="StartingDate", type="datetime", nullable=false)
     */
    private $startingdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EndingDate", type="datetime", nullable=false)
     */
    private $endingdate;

    /**
     * @var \Rooms
     *
     * @ORM\ManyToOne(targetEntity="Rooms")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="RoomID", referencedColumnName="ID")
     * })
     */
    private $roomid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMaxparticipans(): ?int
    {
        return $this->maxparticipans;
    }

    public function setMaxparticipans(int $maxparticipans): self
    {
        $this->maxparticipans = $maxparticipans;

        return $this;
    }

    public function getStartingdate(): ?\DateTimeInterface
    {
        return $this->startingdate;
    }

    public function setStartingdate(\DateTimeInterface $startingdate): self
    {
        $this->startingdate = $startingdate;

        return $this;
    }

    public function getEndingdate(): ?\DateTimeInterface
    {
        return $this->endingdate;
    }

    public function setEndingdate(\DateTimeInterface $endingdate): self
    {
        $this->endingdate = $endingdate;

        return $this;
    }

    public function getRoomid(): ?Rooms
    {
        return $this->roomid;
    }

    public function setRoomid(?Rooms $roomid): self
    {
        $this->roomid = $roomid;

        return $this;
    }


}
