<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity
 */
class AvailabilityHours
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $day_of_week;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $open_at;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $close_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Banner", inversedBy="availabilitiesHours")
     * @ORM\JoinColumn(name="banner_id", referencedColumnName="id")
     */
    private $banner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayOfWeek(): ?int
    {
        return $this->day_of_week;
    }

    public function setDayOfWeek(int $day_of_week): self
    {
        $this->day_of_week = $day_of_week;

        return $this;
    }

    public function getOpenAt(): ?\DateTimeInterface
    {
        return $this->open_at;
    }

    public function setOpenAt(\DateTimeInterface $open_at): self
    {
        $this->open_at = $open_at;

        return $this;
    }

    public function getCloseAt(): ?\DateTimeInterface
    {
        return $this->close_at;
    }

    public function setCloseAt(\DateTimeInterface $close_at): self
    {
        $this->close_at = $close_at;

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