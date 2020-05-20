<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("title", message="Une salle utilise déjà ce nom.")
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 */
class Room
{
    const AUTHORIZATION = [
        0 => "Nécessite une autorisation",
        1 => "Ne nécessite pas d'autorisation"
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $capacity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="room")
     */
    private $bookings;

    /**
     * 0 -> Nécessite une autorisation
     * 1 -> Ne nécessite pas d'autorisation
     * @ORM\Column(type="boolean")
     */
    private $authorization;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", inversedBy="rooms")
     */
    private $options;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setRoom($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getRoom() === $this) {
                $booking->setRoom(null);
            }
        }

        return $this;
    }

    public function getAuthorization(): ?bool
    {
        return $this->authorization;
    }

    public function setAuthorization(bool $authorization): self
    {
        $this->authorization = $authorization;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addRoom($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            $option->removeRoom($this);
        }

        return $this;
    }
}
