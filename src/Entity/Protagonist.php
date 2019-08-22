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
 * @UniqueEntity("name", message="violation.name.not_unique")
 * @UniqueEntity("slug", message="violation.slug.not_unique")
 */
class Protagonist extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="protagonists")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Blameable(on="create")
     *
     * @var User|null
     */
    protected ?User $user;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="protagonist")
     *
     * @var Collection
     */
    protected Collection $posts;

    /**
     * @ORM\Column(type="string", length=31, nullable=false, unique=true)
     *
     * @Assert\NotBlank(message="violation.username.blank")
     * @Assert\Regex(
     *     pattern="/^[ a-zA-Z0-9éÉèÈêÊëËäÄâÂàÀïÏöÖôÔüÜûÛçÇ']+$/",
     *     message="violation.name.invalid_characters"
     * )
     * @Assert\Length(
     *     min=1,
     *     max=30,
     *     minMessage="violation.name.too_short",
     *     maxMessage="violation.name.too_long"
     * )
     *
     * @var string|null
     */
    protected ?string $name;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"name"})
     *
     * @var string|null
     */
    protected ?string $slug;

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
    protected ?string $avatar;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected bool $anonymous;

    /**
     * Protagonist constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @param Collection $posts
     */
    public function setPosts(Collection $posts): void
    {
        $this->posts = $posts;
    }

    /**
     * @param Post $post
     */
    public function addPost(Post $post): void
    {
        $this->posts->add($post);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return bool
     */
    public function isAnonymous(): bool
    {
        return $this->anonymous;
    }

    /**
     * @param bool $anonymous
     */
    public function setAnonymous(bool $anonymous): void
    {
        $this->anonymous = $anonymous;
    }
}
