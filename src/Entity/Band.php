<?php

namespace App\Entity;

use App\Contract\SlugSourceInterface;
use App\Repository\BandRepository;
use App\Entity\Traits\TimestampedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BandRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Band implements SlugSourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use TimestampedEntity;

    #[ORM\Column(length: 128)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $links = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $cover_image = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    private ?string $music_brainz_id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    private ?string $spotify_id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $active_from = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $active_until = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_disbanded = false;

    /**
     * @var Collection<int, Musician>
     */
    #[ORM\ManyToMany(targetEntity: Musician::class, inversedBy: 'bands')]
    #[ORM\JoinTable(name: 'band_musician')]
    private Collection $musicians;

    /**
     * @var Collection<int, Album>
     */
    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'bands')]
    #[ORM\JoinTable(name: 'band_album')]
    private Collection $albums;

    /**
     * @var Collection<int, Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'bands')]
    #[ORM\JoinTable(name: 'band_song')]
    private Collection $songs;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'bands')]
    #[ORM\JoinTable(name: 'band_event')]
    private Collection $events;

    /**
     * @var Collection<int, Medium>
     */
    #[ORM\ManyToMany(targetEntity: Medium::class, inversedBy: 'bands')]
    #[ORM\JoinTable(name: 'band_medium')]
    private Collection $media;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'bands')]
    #[ORM\JoinTable(name: 'band_genre')]
    private Collection $genres;

    public function __construct()
    {
        $this->musicians = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->songs = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->genres = new ArrayCollection();
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

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

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

    public function getCoverImage(): ?string
    {
        return $this->cover_image;
    }

    public function setCoverImage(?string $cover_image): static
    {
        $this->cover_image = $cover_image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getSlugSource(): ?string
    {
        return $this->name;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getMusicBrainzId(): ?string
    {
        return $this->music_brainz_id;
    }

    public function setMusicBrainzId(?string $musicBrainzId): void
    {
        $this->music_brainz_id = $musicBrainzId;
    }

    public function getSpotifyId(): ?string
    {
        return $this->spotify_id;
    }

    public function setSpotifyId(?string $spotifyId): void
    {
        $this->spotify_id = $spotifyId;
    }

    public function getActiveFrom(): ?\DateTimeImmutable
    {
        return $this->active_from;
    }

    public function setActiveFrom(?\DateTimeImmutable $activeFrom): static
    {
        $this->active_from = $activeFrom;

        return $this;
    }

    public function getActiveUntil(): ?\DateTimeImmutable
    {
        return $this->active_until;
    }

    public function setActiveUntil(?\DateTimeImmutable $activeUntil): static
    {
        $this->active_until = $activeUntil;

        return $this;
    }

    public function isDisbanded(): bool
    {
        return $this->is_disbanded;
    }

    public function setIsDisbanded(bool $isDisbanded): static
    {
        $this->is_disbanded = $isDisbanded;

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
            $musician->addBand($this);
        }

        return $this;
    }

    public function removeMusician(Musician $musician): static
    {
        if ($this->musicians->removeElement($musician)) {
            $musician->removeBand($this);
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
            $album->addBand($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            $album->removeBand($this);
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
            $song->addBand($this);
        }

        return $this;
    }

    public function removeSong(Song $song): static
    {
        if ($this->songs->removeElement($song)) {
            $song->removeBand($this);
        }

        return $this;
    }

    public function clearSongs(): self
    {
        $this->songs->clear();
        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addBand($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeBand($this);
        }

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
            $medium->addBand($this);
        }

        return $this;
    }

    public function removeMedium(Medium $medium): static
    {
        if ($this->media->removeElement($medium)) {
            $medium->removeBand($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
            $genre->addBand($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeBand($this);
        }

        return $this;
    }
}
