<?php

namespace App\Entity;

use App\Repository\EventRepository;
use App\Entity\Traits\TimestampedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use TimestampedEntity;

    #[ORM\Column(length: 128)]
    private ?string $name = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $links = null;

    /**
     * @var Collection<int, Band>
     */
    #[ORM\ManyToMany(targetEntity: Band::class, mappedBy: 'events')]
    private Collection $bands;

    /**
     * @var Collection<int, Musician>
     */
    #[ORM\ManyToMany(targetEntity: Musician::class, mappedBy: 'events')]
    private Collection $musicians;

    /**
     * @var Collection<int, Medium>
     */
    #[ORM\ManyToMany(targetEntity: Medium::class, inversedBy: 'events')]
    #[ORM\JoinTable(name: 'event_medium')]
    private Collection $media;

    public function __construct()
    {
        $this->bands = new ArrayCollection();
        $this->musicians = new ArrayCollection();
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLinks(): ?array
    {
        return $this->links;
    }

    public function setLinks(?array $links): static
    {
        $this->links = $links;

        return $this;
    }

    /**
     * @return Collection<int, Band>
     */
    public function getBands(): Collection
    {
        return $this->bands;
    }

    public function addBand(Band $band): static
    {
        if (!$this->bands->contains($band)) {
            $this->bands->add($band);
        }

        return $this;
    }

    public function removeBand(Band $band): static
    {
        $this->bands->removeElement($band);

        return $this;
    }

    /**
     * @return Collection<int, Musician>
     */
    public function getMusicians(): Collection
    {
        return $this->musicians;
    }

    public function addMusician(Musician $musician): static
    {
        if (!$this->musicians->contains($musician)) {
            $this->musicians->add($musician);
        }

        return $this;
    }

    public function removeMusician(Musician $musician): static
    {
        $this->musicians->removeElement($musician);

        return $this;
    }

    /**
     * @return Collection<int, Medium>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Medium $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->addEvent($this);
        }

        return $this;
    }

    public function removeMedium(Medium $medium): static
    {
        if ($this->media->removeElement($medium)) {
            $medium->removeEvent($this);
        }

        return $this;
    }
}
