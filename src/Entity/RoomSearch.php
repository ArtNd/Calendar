<?php

namespace App\Entity;


use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class RoomSearch
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var DateTime
     */
    private $dateFrom;

    /**
     * @var DateTime
     * @Assert\GreaterThan(propertyPath="dateFrom")
     */
    private $dateTo;

    /**
     * @var int|null
     */
    private $minCapacity;

    /**
     * @var ArrayCollection
     */
    private $options;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return RoomSearch
     */
    public function setTitle(string $title): RoomSearch
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateFrom(): ?DateTime
    {
        return $this->dateFrom;
    }

    /**
     * @param DateTime $dateFrom
     * @return RoomSearch
     */
    public function setDateFrom(DateTime $dateFrom): RoomSearch
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateTo(): ?DateTime
    {
        return $this->dateTo;
    }

    /**
     * @param DateTime $dateTo
     * @return RoomSearch
     */
    public function setDateTo(DateTime $dateTo): RoomSearch
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinCapacity(): ?int
    {
        return $this->minCapacity;
    }

    /**
     * @param int|null $minCapacity
     * @return RoomSearch
     */
    public function setMinCapacity(int $minCapacity): RoomSearch
    {
        $this->minCapacity = $minCapacity;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getOptions(): ArrayCollection
    {
        return $this->options;
    }

    /**
     * @param ArrayCollection $options
     * @return RoomSearch
     */
    public function setOptions(ArrayCollection $options): RoomSearch
    {
        $this->options = $options;
        return $this;
    }
}