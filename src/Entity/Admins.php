<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admins
 *
 * @ORM\Table(name="admins")
 * @ORM\Entity
 */
class Admins
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
     * @var string
     *
     * @ORM\Column(name="AuthToken", type="string", length=255, nullable=false)
     */
    private $authtoken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthtoken(): ?string
    {
        return $this->authtoken;
    }

    public function setAuthtoken(string $authtoken): self
    {
        $this->authtoken = $authtoken;

        return $this;
    }


}
