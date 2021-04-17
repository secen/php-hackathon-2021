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


}
