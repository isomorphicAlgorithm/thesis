<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use App\Entity\Traits\TimestampedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use TimestampedEntity;

    #[ORM\Column(length: 128)]
    private ?string $link = null;

    /**
     * @var Collection<int, Band>
     */
    #[ORM\ManyToMany(targetEntity: Band::class, inversedBy: 'media')]
    private Collection $band_id;

    /**
     * @var Collection<int, Musician>
     */
    #[ORM\ManyToMany(targetEntity: Musician::class, inversedBy: 'media')]
    private Collection $musician_id;

    /**
     * @var Collection<int, Album>
     */
    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'media')]
    private Collection $album_id;

    /**
     * @var Collection<int, Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'media')]
    private Collection $song_id;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'media')]
    private Collection $event_id;

    public function __construct()
    {
        $this->band_id = new ArrayCollection();
        $this->musician_id = new ArrayCollection();
        $this->album_id = new ArrayCollection();
        $this->song_id = new ArrayCollection();
        $this->event_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection<int, Band>
     */
    public function getBandId(): Collection
    {
        return $this->band_id;
    }

    public function addBandId(Band $bandId): static
    {
        if (!$this->band_id->contains($bandId)) {
            $this->band_id->add($bandId);
        }

        return $this;
    }

    public function removeBandId(Band $bandId): static
    {
        $this->band_id->removeElement($bandId);

        return $this;
    }

    /**
     * @return Collection<int, Musician>
     */
    public function getMusicianId(): Collection
    {
        return $this->musician_id;
    }

    public function addMusicianId(Musician $musicianId): static
    {
        if (!$this->musician_id->contains($musicianId)) {
            $this->musician_id->add($musicianId);
        }

        return $this;
    }

    public function removeMusicianId(Musician $musicianId): static
    {
        $this->musician_id->removeElement($musicianId);

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAlbumId(): Collection
    {
        return $this->album_id;
    }

    public function addAlbumId(Album $albumId): static
    {
        if (!$this->album_id->contains($albumId)) {
            $this->album_id->add($albumId);
        }

        return $this;
    }

    public function removeAlbumId(Album $albumId): static
    {
        $this->album_id->removeElement($albumId);

        return $this;
    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongId(): Collection
    {
        return $this->song_id;
    }

    public function addSongId(Song $songId): static
    {
        if (!$this->song_id->contains($songId)) {
            $this->song_id->add($songId);
        }

        return $this;
    }

    public function removeSongId(Song $songId): static
    {
        $this->song_id->removeElement($songId);

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventId(): Collection
    {
        return $this->event_id;
    }

    public function addEventId(Event $eventId): static
    {
        if (!$this->event_id->contains($eventId)) {
            $this->event_id->add($eventId);
        }

        return $this;
    }

    public function removeEventId(Event $eventId): static
    {
        $this->event_id->removeElement($eventId);

        return $this;
    }
}
