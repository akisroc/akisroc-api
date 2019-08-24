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
    public ?Place $place = null;

    /**
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="story", cascade={"remove"})
     *
     * @Assert\Count(min=1, minMessage="violation.story.no_episode")
     *
     * @var Collection|null
     */
    protected ?Collection $episodes = null;

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
    public ?string $title = null;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @var string|null
     */
    public ?string $slug = null;

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
     * @return Collection
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    /**
     * @param Episode $episode
     * @return void
     */
    public function addEpisode(Episode $episode): void
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes[] = $episode;
            $episode->story = $this;
        }
    }

    /**
     * @param Episode $episode
     * @return void
     */
    public function removeEpisode(Episode $episode): void
    {
        if ($this->episodes->contains($episode)) {
            $this->episodes->removeElement($episode);
            if ($episode->story === $this) {
                $episode->story = null;
            }
        }
    }
}
