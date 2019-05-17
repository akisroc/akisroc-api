<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Thread extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="threads")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotBlank(message="violation.category.blank")
     *
     * @var Category|null
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="thread", cascade={"remove"})
     *
     * @Assert\Count(min=1)
     *
     * @var Collection
     */
    protected $posts;

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
     * Thread constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title ?: '';
    }
}
