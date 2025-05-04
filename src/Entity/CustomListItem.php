<?php

namespace App\Entity;

use App\Repository\CustomListItemRepository;
use App\Entity\Traits\TimestampedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomListItemRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CustomListItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use TimestampedEntity;

    /**
     * @var Collection<int, CustomList>
     */
    #[ORM\ManyToMany(targetEntity: CustomList::class, inversedBy: 'custom_list_items')]
    private Collection $custom_list_id;

    /**
     * @var Collection<int, Album>
     */
    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'custom_list_items')]
    private Collection $album_id;

    /**
     * @var Collection<int, Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'custom_list_items')]
    private Collection $song_id;

    public function __construct()
    {
        $this->custom_list_id = new ArrayCollection();
        $this->album_id = new ArrayCollection();
        $this->song_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CustomList>
     */
    public function getCustomListId(): Collection
    {
        return $this->custom_list_id;
    }

    public function addCustomListId(CustomList $customListId): static
    {
        if (!$this->custom_list_id->contains($customListId)) {
            $this->custom_list_id->add($customListId);
        }

        return $this;
    }

    public function removeCustomListId(CustomList $customListId): static
    {
        $this->custom_list_id->removeElement($customListId);

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
}
