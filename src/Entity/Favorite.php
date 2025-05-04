<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use App\Entity\Traits\TimestampedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Favorite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use TimestampedEntity;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    private ?User $user_id = null;

    /**
     * @var Collection<int, Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'favorites')]
    private Collection $song_id;

    public function __construct()
    {
        $this->song_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

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
