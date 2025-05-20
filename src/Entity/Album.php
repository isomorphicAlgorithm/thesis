<?php

namespace App\Entity;

use App\Contract\SlugSourceInterface;
use App\Repository\AlbumRepository;
use App\Entity\Traits\TimestampedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Album implements SlugSourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use TimestampedEntity;

    #[ORM\Column(length: 128)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $release_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null; //seconds

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $cover_image = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $links = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    private ?string $spotify_id = null;

    /**
     * @var Collection<int, Band>
     */
    #[ORM\ManyToMany(targetEntity: Band::class, inversedBy: 'albums')]
    private Collection $bands;

    /**
     * @var Collection<int, Musician>
     */
    #[ORM\ManyToMany(targetEntity: Musician::class, inversedBy: 'albums')]
    private Collection $musicians;

    /**
     * @var Collection<int, Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class, mappedBy: 'albums')]
    private Collection $songs;

    /**
     * @var Collection<int, Rating>
     */
    #[ORM\ManyToMany(targetEntity: Rating::class, mappedBy: 'albums')]
    private Collection $ratings;

    /**
     * @var Collection<int, CustomListItem>
     */
    #[ORM\ManyToMany(targetEntity: CustomListItem::class, mappedBy: 'albums')]
    private Collection $custom_list_items;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\ManyToMany(targetEntity: Media::class, mappedBy: 'albums')]
    private Collection $media;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, mappedBy: 'albums')]
    private Collection $genres;

    public function __construct()
    {
        $this->bands = new ArrayCollection();
        $this->musicians = new ArrayCollection();
        $this->songs = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->custom_list_items = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->release_date;
    }

    public function setReleaseDate(?\DateTimeImmutable $releaseDate): static
    {
        $this->release_date = $releaseDate;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getSlugSource(): ?string
    {
        return $this->title;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSpotifyId(): ?string
    {
        return $this->spotify_id;
    }

    public function setSpotifyId(?string $spotifyId): void
    {
        $this->spotify_id = $spotifyId;
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
            $song->addAlbum($this);
        }

        return $this;
    }

    public function removeSong(Song $song): static
    {
        if ($this->songs->removeElement($song)) {
            $song->removeAlbum($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->addAlbum($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            $rating->removeAlbum($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomListItem>
     */
    public function getCustomListItems(): Collection
    {
        return $this->custom_list_items;
    }

    public function addCustomListItem(CustomListItem $customListItem): static
    {
        if (!$this->custom_list_items->contains($customListItem)) {
            $this->custom_list_items->add($customListItem);
            $customListItem->addAlbum($this);
        }

        return $this;
    }

    public function removeCustomListItem(CustomListItem $customListItem): static
    {
        if ($this->custom_list_items->removeElement($customListItem)) {
            $customListItem->removeAlbum($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->addAlbum($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): static
    {
        if ($this->media->removeElement($medium)) {
            $medium->removeAlbum($this);
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
            $genre->addAlbum($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeAlbum($this);
        }

        return $this;
    }
}
