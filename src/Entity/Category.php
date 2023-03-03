<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @Gedmo\Slug(fields={"name"})
     */
    #[ORM\Column(length:128,unique:true)]
    private ?string $slug;

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->name = $slug;
    }    

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

    /*
     * When a new entity instance is created and the ManyToMany or OneToMany relationship property is not initialized,
     * an error may occur when trying to manipulate the uninitialized property.
     * creating a constructor function, it ensure ensure that all the properties are properly initialized when a new instance is created, including ManyToMany and OneToMany relationships.
     */

     
    public function __construct()
    {
        $this->affiliates = new ArrayCollection();
        $this->jobs = new ArrayCollection();
    }


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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * 
     * @return self
     */
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

    /**
     * @param Affiliate $affiliate
     *
     * @return self
     */
    public function addAffiliate(Affiliate $affiliate): self
    {
        if (!$this->affiliates->contains($affiliate)) {
            $this->affiliates->add($affiliate);
        }

        return $this;
    }

    /**
     * @param Affiliate $affiliate
     *
     * @return self
     */
    public function removeAffiliate(Affiliate $affiliate): self
    {
        $this->affiliates->removeElement($affiliate);

        return $this;
    }

	/**
	 * @return Collection<int, Job>
	 */
	public function getJobs(): Collection
    {
		return $this->jobs;
	}
	
	/**
	 * @param Job $job 
     * 
	 * @return self
	 */
	public function addJob(Job $job): self 
    {
		if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
        }
		return $this;
	}

    /**
	 * @param Job $job 
     * 
	 * @return self
	 */
	public function removeJob(Job $job): self 
    {
        $this->jobs->removeElement($job);

		return $this;
	}

    /**
     * @return Job[]|ArrayCollection
     */
    public function getActiveJobs()
    {
        return $this->jobs->filter(function(Job $job)
        {
            return $job->getExpiresAt()> new DateTime();
        });
    }
    
}