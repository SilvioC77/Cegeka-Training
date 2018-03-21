<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\FlightRepository")
 */
class Flight
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agency", inversedBy="flights")
     */
    private $agency;

    /**
     * @ORM\Column(type="integer")
     */
    private $remoteId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $airline;

    /**
     * @ORM\Column(type="date")
     */
    private $start;

    /**
     * @ORM\Column(type="date")
     */
    private $end;

    /**
     * @ORM\Column(type="time")
     */
    private $timeofday;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $flightfrom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $flightto;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $owned;

    public function getId()
    {
        return $this->id;
    }

    public function getAgency(): ?int
    {
        return $this->agency;
    }

    public function setAgency(int $agency): self
    {
        $this->agency = $agency;

        return $this;
    }

    public function getRemoteId(): ?int
    {
        return $this->remoteId;
    }

    public function setRemoteId(int $remoteId): self
    {
        $this->remoteId = $remoteId;

        return $this;
    }

    public function getAirline(): ?string
    {
        return $this->airline;
    }

    public function setAirline(string $airline): self
    {
        $this->airline = $airline;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getTimeofday(): ?\DateTimeInterface
    {
        return $this->timeofday;
    }

    public function setTimeofday(\DateTimeInterface $timeofday): self
    {
        $this->timeofday = $timeofday;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getFlightfrom(): ?string
    {
        return $this->flightfrom;
    }

    public function setFlightfrom(string $flightfrom): self
    {
        $this->flightfrom = $flightfrom;

        return $this;
    }

    public function getFlightto(): ?string
    {
        return $this->flightto;
    }

    public function setFlightto(string $flightto): self
    {
        $this->flightto = $flightto;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOwned(): ?bool
    {
        return $this->owned;
    }

    public function setOwned(bool $owned): self
    {
        $this->owned = $owned;

        return $this;
    }

    public function __toString()
    {
     return $this->airline;
    }
}
