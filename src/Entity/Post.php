<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(columns={"content"}, flags={"fulltext"})
 *     }
 * )
 */
class Post extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Thread", inversedBy="posts", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Thread|null
     */
    public ?Thread $thread = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Blameable(on="create")
     *
     * @var User|null
     */
    public ?User $author = null;

    /**
     * @ORM\Column(type="text", length=16383, nullable=false)
     *
     * @Assert\NotBlank(message="violation.content.blank")
     * @Assert\Length(
     *     min=1,
     *     max=16350,
     *     minMessage="violation.content.too_short",
     *     maxMessage="violation.content.too_long"
     * )
     *
     * @var string|null
     */
    public ?string $content = null;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->content ?: '';
    }
}
