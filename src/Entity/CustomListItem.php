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
    private Collection $custom_lists;

    /**
     * @var Collection<int, Album>
     */
    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'custom_list_items')]
    private Collection $albums;

    /**
     * @var Collection<int, Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'custom_list_items')]
    private Collection $songs;

    public function __construct()
    {
        $this->custom_lists = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CustomList>
     */
    public function getCustomLists(): Collection
    {
        return $this->custom_lists;
    }

    public function addCustomList(CustomList $customList): static
    {
        if (!$this->custom_lists->contains($customList)) {
            $this->custom_lists->add($customList);
        }

        return $this;
    }

    public function removeCustomList(CustomList $customList): static
    {
        $this->custom_lists->removeElement($customList);

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
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        $this->albums->removeElement($album);

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
        }

        return $this;
    }

    public function removeSong(Song $song): static
    {
        $this->songs->removeElement($song);

        return $this;
    }
}
