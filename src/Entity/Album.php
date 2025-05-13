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

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $genre = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $cover_image = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $links = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    /**
     * @var Collection<int, Band>
     */
    #[ORM\ManyToMany(targetEntity: Band::class, inversedBy: 'albums')]
    private Collection $band_id;

    /**
     * @var Collection<int, Musician>
     */
    #[ORM\ManyToMany(targetEntity: Musician::class, inversedBy: 'albums')]
    private Collection $musician_id;

    /**
     * @var Collection<int, Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class, mappedBy: 'album_id')]
    private Collection $songs;

    /**
     * @var Collection<int, Rating>
     */
    #[ORM\ManyToMany(targetEntity: Rating::class, mappedBy: 'album_id')]
    private Collection $ratings;

    /**
     * @var Collection<int, CustomListItem>
     */
    #[ORM\ManyToMany(targetEntity: CustomListItem::class, mappedBy: 'album_id')]
    private Collection $custom_list_items;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\ManyToMany(targetEntity: Media::class, mappedBy: 'album_id')]
    private Collection $media;

    public function __construct()
    {
        $this->band_id = new ArrayCollection();
        $this->musician_id = new ArrayCollection();
        $this->songs = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->custom_list_items = new ArrayCollection();
        $this->media = new ArrayCollection();
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

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): static
    {
        $this->genre = $genre;

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
            $song->addAlbumId($this);
        }

        return $this;
    }

    public function removeSong(Song $song): static
    {
        if ($this->songs->removeElement($song)) {
            $song->removeAlbumId($this);
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
            $rating->addAlbumId($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            $rating->removeAlbumId($this);
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
            $customListItem->addAlbumId($this);
        }

        return $this;
    }

    public function removeCustomListItem(CustomListItem $customListItem): static
    {
        if ($this->custom_list_items->removeElement($customListItem)) {
            $customListItem->removeAlbumId($this);
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
            $medium->addAlbumId($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): static
    {
        if ($this->media->removeElement($medium)) {
            $medium->removeAlbumId($this);
        }

        return $this;
    }
}
