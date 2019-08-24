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
    public ?User $user = null;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="protagonist")
     *
     * @var Collection|null
     */
    protected ?Collection $posts = null;

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
    public ?string $name = null;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"name"})
     *
     * @var string|null
     */
    public ?string $slug = null;

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
    public ?string $avatar = null;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public bool $anonymous = true;

    /**
     * Protagonist constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     * @return void
     */
    public function addPost(Post $post): void
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->protagonist = $this;
        }
    }

    /**
     * @param Post $post
     * @return void
     */
    public function removePost(Post $post): void
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            if ($post->protagonist === $this) {
                $post->protagonist = null;
            }
        }
    }
}
