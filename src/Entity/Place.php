<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 * @UniqueEntity("title", message="violation.title.not_unique")
 * @UniqueEntity("slug", message="violation.slug.not_unique")
 */
class Place extends AbstractEntity
{
    /**
     * @ORM\OneToMany(targetEntity="Story", mappedBy="Place", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     *
     * @var Collection|null
     */
    protected ?Collection $stories = null;

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
     * @ORM\Column(type="string", length=511, nullable=false)
     *
     * @Assert\NotBlank(message="violation.description.blank")
     * @Assert\Length(
     *     min=1,
     *     max=500,
     *     minMessage="violation.description.too_short",
     *     maxMessage="violation.description.too_long"
     * )
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     *
     * @Assert\Url(message="violation.uri.wrong_format")
     * @Assert\Length(
     *     min=1,
     *     max=500,
     *     minMessage="violation.uri.too_short",
     *     maxMessage="violation.uri.too_long"
     * )
     *
     * @var string|null
     */
    public ?string $image = null;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @var string|null
     */
    public ?string $slug = null;

    /**
     * Place constructor.
     */
    public function __construct()
    {
        $this->stories = new ArrayCollection();
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
    public function getStories(): Collection
    {
        return $this->stories;
    }

    /**
     * @param Story $story
     * @return void
     */
    public function addStory(Story $story): void
    {
        if (!$this->stories->contains($story)) {
            $this->stories[] = $story;
            $story->place = $this;
        }
    }

    /**
     * @param Story $story
     * @return void
     */
    public function removeStory(Story $story): void
    {
        if ($this->stories->contains($story)) {
            $this->stories->removeElement($story);
            if ($story->place === $this) {
                $story->place = null;
            }
        }
    }
}
