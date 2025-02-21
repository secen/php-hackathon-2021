<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="ProgrammeID", columns={"ProgrammeID"}), @ORM\Index(name="UserID", columns={"UserID"})})
 * @ORM\Entity
 */
class Reservation
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
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UserID", referencedColumnName="ID")
     * })
     */
    private $userid;

    /**
     * @var \Programmes
     *
     * @ORM\ManyToOne(targetEntity="Programmes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ProgrammeID", referencedColumnName="ID")
     * })
     */
    private $programmeid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserid(): ?Users
    {
        return $this->userid;
    }

    public function setUserid(?Users $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getProgrammeid(): ?Programmes
    {
        return $this->programmeid;
    }

    public function setProgrammeid(?Programmes $programmeid): self
    {
        $this->programmeid = $programmeid;

        return $this;
    }


}
