<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @UniqueEntity("title", message="violation.title.not_unique")
 * @UniqueEntity("slug", message="violation.slug.not_unique")
 */
class Category extends AbstractEntity
{
    /**
     * @ORM\OneToMany(targetEntity="Thread", mappedBy="category", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     *
     * @var Collection
     */
    protected Collection $threads;

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
    protected ?string $title;

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
    protected ?string $description;

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
    protected ?string $image;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @var string|null
     */
    protected ?string $slug;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->threads = new ArrayCollection();
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
    public function getThreads(): Collection
    {
        return $this->threads;
    }

    /**
     * @param Collection $threads
     */
    public function setThreads(Collection $threads): void
    {
        $this->threads = $threads;
    }

    /**
     * @param Thread $thread
     */
    public function addThread(Thread $thread): void
    {
        $this->threads->add($thread);
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
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
