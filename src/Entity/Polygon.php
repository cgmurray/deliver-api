<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity
 */
class Polygon
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4096, nullable=false)
     */
    private $polygon_coordinates;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Banner", inversedBy="polygons")
     * @ORM\JoinColumn(name="banner_id", referencedColumnName="id", nullable=false)
     */
    private $banner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPolygonCoordinates(): ?string
    {
        return $this->polygon_coordinates;
    }

    public function setPolygonCoordinates(string $polygon_coordinates): self
    {
        $this->polygon_coordinates = $polygon_coordinates;

        return $this;
    }

    public function getBanner(): ?Banner
    {
        return $this->banner;
    }

    public function setBanner(?Banner $banner): self
    {
        $this->banner = $banner;

        return $this;
    }
}