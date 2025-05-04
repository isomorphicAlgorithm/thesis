<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use App\Entity\Traits\TimestampedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use TimestampedEntity;

    #[ORM\Column]
    private ?int $rating_score = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $review = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    private ?User $user_id = null;

    /**
     * @var Collection<int, Album>
     */
    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'ratings')]
    private Collection $album_id;

    public function __construct()
    {
        $this->album_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRatingScore(): ?int
    {
        return $this->rating_score;
    }

    public function setRatingScore(int $ratingScore): static
    {
        $this->rating_score = $ratingScore;

        return $this;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): static
    {
        $this->review = $review;

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

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}
