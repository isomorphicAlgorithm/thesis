<?php

namespace App\Entity;

use App\Repository\CustomListRepository;
use App\Entity\Traits\TimestampedEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomListRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CustomList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    use TimestampedEntity;

    #[ORM\Column(length: 128)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $is_public = false;

    #[ORM\ManyToOne(inversedBy: 'custom_lists')]
    private ?User $user = null;

    /**
     * @var Collection<int, CustomListItem>
     */
    #[ORM\ManyToMany(targetEntity: CustomListItem::class, mappedBy: 'custom_lists')]
    private Collection $custom_list_items;

    public function __construct()
    {
        $this->custom_list_items = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->is_public;
    }

    public function setIsPublic(bool $is_public): static
    {
        $this->is_public = $is_public;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
            $customListItem->addCustomList($this);
        }

        return $this;
    }

    public function removeCustomListItem(CustomListItem $customListItem): static
    {
        if ($this->custom_list_items->removeElement($customListItem)) {
            $customListItem->removeCustomList($this);
        }

        return $this;
    }
}
