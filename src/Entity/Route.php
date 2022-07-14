<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RouteRepository::class)
 */
class Route
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $airline;

    /**
     * @ORM\Column(type="integer")
     */
    private $airlineId;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $sourceAirport;

    /**
     * @ORM\Column(type="integer")
     */
    private $sourceAirportId;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $destinationAirport;

    /**
     * @ORM\Column(type="integer")
     */
    private $destinationAirportId;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $codeshare;

    /**
     * @ORM\Column(type="integer")
     */
    private $stops;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $equipment;

    /**
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $routecode;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAirlineId(): ?int
    {
        return $this->airlineId;
    }

    public function setAirlineId(int $airlineId): self
    {
        $this->airlineId = $airlineId;

        return $this;
    }

    public function getSourceAirport(): ?string
    {
        return $this->sourceAirport;
    }

    public function setSourceAirport(string $sourceAirport): self
    {
        $this->sourceAirport = $sourceAirport;

        return $this;
    }

    public function getSourceAirportId(): ?int
    {
        return $this->sourceAirportId;
    }

    public function setSourceAirportId(int $sourceAirportId): self
    {
        $this->sourceAirportId = $sourceAirportId;

        return $this;
    }

    public function getDestinationAirport(): ?string
    {
        return $this->destinationAirport;
    }

    public function setDestinationAirport(string $destinationAirport): self
    {
        $this->destinationAirport = $destinationAirport;

        return $this;
    }

    public function getDestinationAirportId(): ?int
    {
        return $this->destinationAirportId;
    }

    public function setDestinationAirportId(int $destinationAirportId): self
    {
        $this->destinationAirportId = $destinationAirportId;

        return $this;
    }

    public function getCodeshare(): ?string
    {
        return $this->codeshare;
    }

    public function setCodeshare(?string $codeshare): self
    {
        $this->codeshare = $codeshare;

        return $this;
    }

    public function getStops(): ?int
    {
        return $this->stops;
    }

    public function setStops(int $stops): self
    {
        $this->stops = $stops;

        return $this;
    }

    public function getEquipment(): ?string
    {
        return $this->equipment;
    }

    public function setEquipment(string $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRoutecode(): ?string
    {
        return $this->routecode;
    }

    public function setRoutecode(string $routecode): self
    {
        $this->routecode = $routecode;

        return $this;
    }
}
