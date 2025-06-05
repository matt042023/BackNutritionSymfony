<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FoodSubGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FoodSubGroupRepository::class)]
#[ORM\UniqueConstraint(fields: ['code'])]
#[ApiResource(
    normalizationContext: ['groups' => ['subGroup:read']]
)]
#[UniqueEntity('code')]
class FoodSubGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['foodGroup:read', 'subGroup:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['foodGroup:read', 'subGroup:read'])]
    private ?int $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['foodGroup:read', 'subGroup:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'foodSubGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FoodGroup $foodGroup = null;

    /**
     * @var Collection<int, FoodSubSubGroup>
     */
    #[ORM\OneToMany(targetEntity: FoodSubSubGroup::class, mappedBy: 'foodSubGroup')]
    #[Groups(['subGroup:read'])]
    private Collection $foodSubSubGroups;

    public function __construct()
    {
        $this->foodSubSubGroups = new ArrayCollection();
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

    public function getFoodGroup(): ?FoodGroup
    {
        return $this->foodGroup;
    }

    public function setFoodGroup(?FoodGroup $foodGroup): static
    {
        $this->foodGroup = $foodGroup;

        return $this;
    }

    /**
     * @return Collection<int, FoodSubSubGroup>
     */
    public function getFoodSubSubGroups(): Collection
    {
        return $this->foodSubSubGroups;
    }

    public function addFoodSubSubGroup(FoodSubSubGroup $foodSubSubGroup): static
    {
        if (!$this->foodSubSubGroups->contains($foodSubSubGroup)) {
            $this->foodSubSubGroups->add($foodSubSubGroup);
            $foodSubSubGroup->setFoodSubGroup($this);
        }

        return $this;
    }

    public function removeFoodSubSubGroup(FoodSubSubGroup $foodSubSubGroup): static
    {
        if ($this->foodSubSubGroups->removeElement($foodSubSubGroup)) {
            // set the owning side to null (unless already changed)
            if ($foodSubSubGroup->getFoodSubGroup() === $this) {
                $foodSubSubGroup->setFoodSubGroup(null);
            }
        }

        return $this;
    }
}
