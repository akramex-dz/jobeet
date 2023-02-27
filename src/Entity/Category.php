<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name:"categories")]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue("AUTO")]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * one category has many job announcements
     * @var Collection<int, Job>
     */
    #[ORM\OneToMany(targetEntity:Job::class, mappedBy: 'category')]
    private Collection $jobs;

    /**
     * one category has many affililiates
     * @var Collection<int, Affiliate>
     */
    #[ORM\ManyToMany(targetEntity: Affiliate::class, mappedBy: 'categories')]
    private Collection|null $affiliates;

    public function __construct()
    {
        $this->affiliates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Affiliate>
     */
    public function getAffiliates(): Collection
    {
        return $this->affiliates; 
    }

    public function addAffiliate(Affiliate $affiliate): self
    {
        if (!$this->affiliates->contains($affiliate)) {
            $this->affiliates->add($affiliate);
            $affiliate->addCategory($this);
        }

        return $this;
    }

    public function removeAffiliate(Affiliate $affiliate): self
    {
        if ($this->affiliates->removeElement($affiliate)) {
            $affiliate->removeCategory($this);
        }

        return $this;
    }
}
