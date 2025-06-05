<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\FoodRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
#[ORM\Table(
    name: 'food',
    uniqueConstraints: [
        new ORM\UniqueConstraint(name: 'uniq_food_code_ssg', columns: ['code', 'sub_sub_group_id'])
    ]
)]
#[ApiResource(
    normalizationContext: ['groups' => ['food:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
#[UniqueEntity('code')]
class Food
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['subSubGroup:read', 'food:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['subSubGroup:read', 'food:read'])]
    private ?int $code = null;

    #[ORM\Column(length: 255)]
    #[Groups(['subSubGroup:read', 'food:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['food:read'])]
    private ?string $nameSci = null;

    #[ORM\ManyToOne(inversedBy: 'foods')]     // ← cohérence relation
    #[ORM\JoinColumn(nullable: false)]
    private ?FoodSubSubGroup $subSubGroup = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['subSubGroup:read', 'food:read'])]
    private ?float $energyKcal = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['subSubGroup:read', 'food:read'])]
    private ?float $proteins = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['subSubGroup:read', 'food:read'])]
    private ?float $carbohydrates = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['subSubGroup:read', 'food:read'])]
    private ?float $lipids = null;

    /*── Getters / setters (inchangés) ──*/

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
    public function getNameSci(): ?string
    {
        return $this->nameSci;
    }
    public function setNameSci(?string $sci): static
    {
        $this->nameSci = $sci;
        return $this;
    }

    public function getSubSubGroup(): ?FoodSubSubGroup
    {
        return $this->subSubGroup;
    }
    public function setSubSubGroup(?FoodSubSubGroup $g): static
    {
        $this->subSubGroup = $g;
        return $this;
    }

    public function getEnergyKcal(): ?float
    {
        return $this->energyKcal;
    }
    public function setEnergyKcal(?float $v): static
    {
        $this->energyKcal = $v;
        return $this;
    }
    public function getProteins(): ?float
    {
        return $this->proteins;
    }
    public function setProteins(?float $v): static
    {
        $this->proteins = $v;
        return $this;
    }
    public function getCarbohydrates(): ?float
    {
        return $this->carbohydrates;
    }
    public function setCarbohydrates(?float $v): static
    {
        $this->carbohydrates = $v;
        return $this;
    }
    public function getLipids(): ?float
    {
        return $this->lipids;
    }
    public function setLipids(?float $v): static
    {
        $this->lipids = $v;
        return $this;
    }
}
