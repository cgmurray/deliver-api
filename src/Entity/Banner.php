<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BannerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=BannerRepository::class)
 */
class Banner
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=false)
     */
    private $filename;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Polygon", mappedBy="banner")
     */
    private $polygons;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AvailabilityHours", mappedBy="banner")
     */
    private $availabilitiesHours;

    /**
     * @var Banner[]
     */
    private $serviceAreaBanners;

    public function __construct()
    {
        $this->polygons = new ArrayCollection();
        $this->availabilitiesHours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return Collection|Polygon[]
     */
    public function getPolygons(): Collection
    {
        return $this->polygons;
    }

    public function addPolygon(Polygon $polygon): self
    {
        if (!$this->polygons->contains($polygon)) {
            $this->polygons[] = $polygon;
            $polygon->setBanner($this);
        }

        return $this;
    }

    public function removePolygon(Polygon $polygon): self
    {
        if ($this->polygons->removeElement($polygon)) {
            // set the owning side to null (unless already changed)
            if ($polygon->getBanner() === $this) {
                $polygon->setBanner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AvailabilityHours[]
     */
    public function getAvailabilitiesHours(): Collection
    {
        return $this->availabilitiesHours;
    }

    public function addAvailabilitiesHour(AvailabilityHours $availabilitiesHour): self
    {
        if (!$this->availabilitiesHours->contains($availabilitiesHour)) {
            $this->availabilitiesHours[] = $availabilitiesHour;
            $availabilitiesHour->setBanner($this);
        }

        return $this;
    }

    public function removeAvailabilitiesHour(AvailabilityHours $availabilitiesHour): self
    {
        if ($this->availabilitiesHours->removeElement($availabilitiesHour)) {
            // set the owning side to null (unless already changed)
            if ($availabilitiesHour->getBanner() === $this) {
                $availabilitiesHour->setBanner(null);
            }
        }

        return $this;
    }

    public function getServiceAreaBanners(string $lat, string $long, int $radiusFeet)
    {
        // ToDo: Create api-platform service layer filter to expose this
        // and connect to BannerRepository->findInServiceArea()
    }
}