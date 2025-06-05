<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiSubresource;
use App\Repository\FoodSubSubGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: FoodSubSubGroupRepository::class)]
#[ORM\UniqueConstraint(fields: ['code'])]
#[ApiResource(
    normalizationContext: ['groups' => ['subSubGroup:read']]
)]
#[UniqueEntity('code')]
class FoodSubSubGroup
{
    /*────────────────────────  Champs scalaires ────────────────────────*/
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['subGroup:read', 'subSubGroup:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['subGroup:read', 'subSubGroup:read'])]
    private ?int $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['subGroup:read', 'subSubGroup:read'])]
    private ?string $name = null;

    /*────────────────────────  Relation montante ───────────────────────*/
    #[ORM\ManyToOne(inversedBy: 'foodSubSubGroups')]
    private ?FoodSubGroup $foodSubGroup = null;

    /*────────────────────────  Relation descendante: FOODS ─────────────*/
    /**
     * @var Collection<int, Food>
     */
    #[ORM\OneToMany(
        mappedBy: 'subSubGroup',
        targetEntity: Food::class,
        cascade: ['persist'],
        orphanRemoval: true
    )]
    #[Groups(['subSubGroup:read'])]
    #[SerializedName('foods')]                 // clé JSON exposée
    private Collection $foods;                 // nom clair et cohérent

    public function __construct()
    {
        $this->foods = new ArrayCollection();
    }

    /*────────────────────────  Getters / setters ───────────────────────*/

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

    public function getFoodSubGroup(): ?FoodSubGroup
    {
        return $this->foodSubGroup;
    }

    public function setFoodSubGroup(?FoodSubGroup $foodSubGroup): static
    {
        $this->foodSubGroup = $foodSubGroup;
        return $this;
    }

    /** @return Collection<int, Food> */
    public function getFoods(): Collection
    {
        return $this->foods;
    }

    public function addFood(Food $food): static
    {
        if (!$this->foods->contains($food)) {
            $this->foods->add($food);
            $food->setSubSubGroup($this);
        }
        return $this;
    }

    public function removeFood(Food $food): static
    {
        if ($this->foods->removeElement($food)) {
            if ($food->getSubSubGroup() === $this) {
                $food->setSubSubGroup(null);
            }
        }
        return $this;
    }
}
