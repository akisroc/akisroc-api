<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoryRepository")
 * @UniqueEntity("title", message="violation.title.not_unique")
 * @UniqueEntity("slug", message="violation.slug.not_unique")
 */
class Story extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="stories")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotBlank(message="violation.place.blank")
     *
     * @var Place|null
     */
    protected $place;

    /**
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="story", cascade={"remove"})
     *
     * @Assert\Count(min=1, minMessage="violation.story.no_episode")
     *
     * @var Collection
     */
    protected $episodes;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Assert\NotBlank(message="violation.description.blank")
     * @Assert\Length(
     *     min=1,
     *     max=60,
     *     minMessage="violation.title.too_short",
     *     maxMessage="violation.title.too_long"
     * )
     *
     * @var string|null
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @var string|null
     */
    protected $slug;

    /**
     * Story constructor.
     */
    public function __construct()
    {
        $this->episodes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title ?: '';
    }

    /**
     * @return Place|null
     */
    public function getPlace(): ?Place
    {
        return $this->place;
    }

    /**
     * @param Place|null $place
     */
    public function setPlace(?Place $place): void
    {
        $this->place = $place;
    }

    /**
     * @return Collection
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    /**
     * @param Episode $episode
     */
    public function addEpisode(Episode $episode): void
    {
        $this->episodes->add($episode);
    }

    /**
     * @param Collection $episodes
     */
    public function setEpisodes(Collection $episodes): void
    {
        $this->episodes = $episodes;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }
}
