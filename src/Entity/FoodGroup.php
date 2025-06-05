<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FoodGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FoodGroupRepository::class)]
#[ORM\UniqueConstraint(fields: ['code'])]
#[ApiResource(
    normalizationContext: ['groups' => ['foodGroup:read']]
)]
#[UniqueEntity('code')]
class FoodGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['foodGroup:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['foodGroup:read'])]
    private ?int $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['foodGroup:read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, FoodSubGroup>
     */
    #[ORM\OneToMany(targetEntity: FoodSubGroup::class, mappedBy: 'foodGroup')]
    #[Groups(['foodGroup:read'])]
    private Collection $foodSubGroups;

    public function __construct()
    {
        $this->foodSubGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): static
    {
        $this->code = $code;

        return $this;
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

    /**
     * @return Collection<int, FoodSubGroup>
     */
    public function getFoodSubGroups(): Collection
    {
        return $this->foodSubGroups;
    }

    public function addFoodSubGroup(FoodSubGroup $foodSubGroup): static
    {
        if (!$this->foodSubGroups->contains($foodSubGroup)) {
            $this->foodSubGroups->add($foodSubGroup);
            $foodSubGroup->setFoodGroup($this);
        }

        return $this;
    }

    public function removeFoodSubGroup(FoodSubGroup $foodSubGroup): static
    {
        if ($this->foodSubGroups->removeElement($foodSubGroup)) {
            // set the owning side to null (unless already changed)
            if ($foodSubGroup->getFoodGroup() === $this) {
                $foodSubGroup->setFoodGroup(null);
            }
        }

        return $this;
    }
}
