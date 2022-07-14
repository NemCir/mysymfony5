<?php

namespace App\Entity;

use App\Entity\City;
use App\Repository\AirportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AirportRepository::class)
 * @ORM\Table(name="airport",uniqueConstraints={@ORM\UniqueConstraint(name="airport_id_index", columns={"airport_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class Airport
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
    private $airportId;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $iata;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $icao;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=6)
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=6)
     */
    private $longitude;

    /**
     * @ORM\Column(type="integer")
     */
    private $altitude;

    /**
     * @ORM\Column(type="integer")
     */
    private $timezone;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $dst;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $tz;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $source;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAirportId(): ?int
    {
        return $this->airportId;
    }

    public function setAirportId(int $airportId): self
    {
        $this->airportId = $airportId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getIata(): ?string
    {
        return $this->iata;
    }

    public function setIata(?string $iata): self
    {
        $this->iata = $iata;

        return $this;
    }

    public function getIcao(): ?string
    {
        return $this->icao;
    }

    public function setIcao(?string $icao): self
    {
        $this->icao = $icao;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAltitude(): ?int
    {
        return $this->altitude;
    }

    public function setAltitude(int $altitude): self
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function getTimezone(): ?int
    {
        return $this->timezone;
    }

    public function setTimezone(int $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getDst(): ?string
    {
        return $this->dst;
    }

    public function setDst(string $dst): self
    {
        $this->dst = $dst;

        return $this;
    }

    public function getTz(): ?string
    {
        return $this->tz;
    }

    public function setTz(string $tz): self
    {
        $this->tz = $tz;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }
}
