<?php

namespace App\Entity;

use App\Contract\SlugSourceInterface;
use App\Repository\SongRepository;
use App\Entity\Traits\TimestampedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Song implements SlugSourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use TimestampedEntity;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $release_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $cover_image = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $links = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    private ?string $spotify_id = null;

    #[ORM\Column]
    private int $track_number;

    /**
     * @var Collection<int, Favorite>
     */
    #[ORM\OneToMany(targetEntity: Favorite::class, mappedBy: 'song')]
    private Collection $favorites;

    /**
     * @var Collection<int, Band>
     */
    #[ORM\ManyToMany(targetEntity: Band::class, mappedBy: 'songs')]
    private Collection $bands;

    /**
     * @var Collection<int, Musician>
     */
    #[ORM\ManyToMany(targetEntity: Musician::class, mappedBy: 'songs')]
    private Collection $musicians;

    /**
     * @var Collection<int, Album>
     */
    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'songs')]
    private Collection $albums;

    /**
     * @var Collection<int, CustomListItem>
     */
    #[ORM\ManyToMany(targetEntity: CustomListItem::class, mappedBy: 'songs')]
    private Collection $customListItems;

    /**
     * @var Collection<int, Medium>
     */
    #[ORM\ManyToMany(targetEntity: Medium::class, inversedBy: 'songs')]
    #[ORM\JoinTable(name: 'song_medium')]
    private Collection $media;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'songs')]
    #[ORM\JoinTable(name: 'song_genre')]
    private Collection $genres;

    public function __construct()
    {
        $this->favorites = new ArrayCollection();
        $this->bands = new ArrayCollection();
        $this->musicians = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->customListItems = new ArrayCollection();
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

    public function getTrackNumber(): int
    {
        return $this->track_number;
    }

    public function setTrackNumber(int $trackNumber): static
    {
        $this->track_number = $trackNumber;

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
            $band->addSong($this);
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
            $musician->addSong($this); // keep it in sync
        }

        return $this;
    }

    public function removeMusician(Musician $musician): static
    {
        $this->musicians->removeElement($musician);

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
            $album->addSong($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        $this->albums->removeElement($album);

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setSong($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            if ($favorite->getSong() === $this) {
                $favorite->setSong(null);
            }
        }

        return $this;
    }

    public function isFavoritedByUser(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        foreach ($this->favorites as $favorite) {
            if ($favorite->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection<int, CustomListItem>
     */
    public function getCustomListItems(): Collection
    {
        return $this->customListItems;
    }

    public function addCustomListItem(CustomListItem $customListItem): static
    {
        if (!$this->customListItems->contains($customListItem)) {
            $this->customListItems->add($customListItem);
            $customListItem->addSong($this);
        }

        return $this;
    }

    public function removeCustomListItem(CustomListItem $customListItem): static
    {
        if ($this->customListItems->removeElement($customListItem)) {
            $customListItem->removeSong($this);
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
            $medium->addSong($this);
        }

        return $this;
    }

    public function removeMedium(Medium $medium): static
    {
        if ($this->media->removeElement($medium)) {
            $medium->removeSong($this);
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
            $genre->addSong($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeSong($this);
        }

        return $this;
    }
}
