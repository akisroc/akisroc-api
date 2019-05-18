<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(columns={"role_play"})
 *     }
 * )
 * @UniqueEntity("title", message="violation.title.not_unique")
 * @UniqueEntity("slug", message="violation.slug.not_unique")
 */
class Category extends AbstractEntity
{
    /**
     * @ORM\OneToMany(targetEntity="Thread", mappedBy="category", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Collection
     */
    protected $threads;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $rolePlay;

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
    protected $description;

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
    protected $image;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @var string|null
     */
    protected $slug;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->threads = new ArrayCollection();
        $this->rolePlay = false;
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
     * @return bool
     */
    public function isRolePlay(): bool
    {
        return $this->rolePlay;
    }

    /**
     * @param bool $rolePlay
     */
    public function setRolePlay(bool $rolePlay): void
    {
        $this->rolePlay = $rolePlay;
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
