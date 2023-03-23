<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Category;
use JMS\Serializer\Annotation as JMS;

#[ORM\Entity(repositoryClass: JobRepository::class)]
#[ORM\Table(name:"jobs")]
#[ORM\HasLifecycleCallbacks()]
#[JMS\ExclusionPolicy("all")]
class Job
{
    public const FULL_TIME_TYPE = 'full-time';
    public const PART_TIME_TYPE = 'part-time';
    public const FREELANCE_TYPE = 'freelance';

    public const TYPES = [
        self::FULL_TIME_TYPE,
        self::PART_TIME_TYPE,
        self::FREELANCE_TYPE,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[JMS\Expose()]
    #[JMS\Type("int")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[JMS\Expose()]
    #[JMS\Type("string")]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[JMS\Expose()]
    #[JMS\Type("string")]
    private ?string $company = null;

    #[ORM\Column(length: 255, nullable: true)]
    private mixed $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[JMS\Expose()]
    #[JMS\Type("string")]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    #[JMS\Expose()]
    #[JMS\Type("string")]
    private ?string $position = null;

    #[ORM\Column(type: Types::TEXT)]
    #[JMS\Expose()]
    #[JMS\Type("string")]
    private ?string $location = null;

    #[ORM\Column(type: Types::TEXT)]
    #[JMS\Expose()]
    #[JMS\Type("string")]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[JMS\Expose()]
    #[JMS\Type("string")]
    private ?string $howToApply = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column]
    private ?bool $public = null;

    #[ORM\Column]
    private ?bool $activated = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[JMS\Expose()]
    #[JMS\Type("DateTime")]
    private ?\DateTimeInterface $expiresAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity:"Category", inversedBy:"jobs")]
    #[ORM\JoinColumn(name:"category_id", referencedColumnName:"id", nullable:false)]
    private Category $category;
    
    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string $company
     *
     * @return self
     */
    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogo(): mixed
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     *
     * @return self
     */
    public function setLogo($logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return self
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string $position
     *
     * @return self
     */
    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }


    /**
     * @return string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     *
     * @return self
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

     /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getHowToApply(): ?string
    {
        return $this->howToApply;
    }

    /**
     * @param string $howToApply
     * 
     * @return self
     */
    public function setHowToApply(string $howToApply): self
    {
        $this->howToApply = $howToApply;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * 
     * @return self
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic(): ?bool
    {
        return $this->public;
    }

    /**
     * @param bool $public
     * 
     * @return self
     */
    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActivated(): ?bool
    {
        return $this->activated;
    }

    /**
     * @param bool $activated
     *
     * @return self
     */
    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTime $expiresAt
     *
     * @return self
     */
    public function setExpiresAt(\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return Category
     */
    public function getCategory() : ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * 
     * @return self
     */
    public function setCategory(Category $category) : self
    {
        $this->category = $category;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        if (!$this->expiresAt) {
            $this->expiresAt = (clone $this->createdAt)->modify('+30 days');
        }
    }
    
    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("logo_path")
     *
     * @return string|null
     */
    public function getLogoPath()
    {
        return $this->getLogo() ? 'uploads/jobs/' . $this->getLogo() : null;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("category_name")
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->getCategory()->getName();
    }
}
