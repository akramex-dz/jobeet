<?php

namespace App\Entity;

use App\Repository\AffiliateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: AffiliateRepository::class)]
#[ORM\Table(name:"affiliates")]
#[ORM\HasLifecycleCallbacks()]
class Affiliate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'affiliates')]
    #[ORM\JoinTable(name:"affiiates_categories")]
    private Collection|null $categories;

    /*
     * When a new entity instance is created and the ManyToMany or OneToMany relationship property is not initialized,
     * an error may occur when trying to manipulate the uninitialized property.
     * creating a constructor function, it ensure ensure that all the properties are properly initialized when a new instance is created, including ManyToMany and OneToMany relationships.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

   /**
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl() : ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function setUrl(string $url) : self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email) : self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken() : ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     *
     * @return self
     */
    public function setToken(?string $token) : self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive() : ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return self
     */
    public function setActive(bool $active) : self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() : ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return Category[]|ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category $category
     *
     * @return self
     */
    public function addCategory(Category $category) : self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    /**
     * @param Category $category
     *
     * @return self
     */
    public function removeCategory(Category $category) : self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
}
