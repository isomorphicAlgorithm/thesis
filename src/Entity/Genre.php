<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use App\Entity\Traits\TimestampedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ORM\Table(name: 'genre')]
#[ORM\HasLifecycleCallbacks]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    use TimestampedEntity;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Band::class, mappedBy: 'genres')]
    private Collection $bands;

    #[ORM\ManyToMany(targetEntity: Musician::class, mappedBy: 'genres')]
    private Collection $musicians;

    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'genres')]
    private Collection $albums;

    #[ORM\ManyToMany(targetEntity: Song::class, mappedBy: 'genres')]
    private Collection $songs;

    public function __construct()
    {
        $this->bands = new ArrayCollection();
        $this->musicians = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $band->addGenre($this);
        }

        return $this;
    }

    public function removeBand(Band $band): static
    {
        if ($this->bands->removeElement($band)) {
            $band->removeGenre($this);
        }

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
            $musician->addGenre($this);
        }

        return $this;
    }

    public function removeMusician(Musician $musician): static
    {
        if ($this->musicians->removeElement($musician)) {
            $musician->removeGenre($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): static
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
            $album->addGenre($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            $album->removeGenre($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): static
    {
        if (!$this->songs->contains($song)) {
            $this->songs->add($song);
            $song->addGenre($this);
        }

        return $this;
    }

    public function removeSong(Song $song): static
    {
        if ($this->songs->removeElement($song)) {
            $song->removeGenre($this);
        }

        return $this;
    }
}
