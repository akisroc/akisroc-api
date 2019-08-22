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
    protected ?Thread $thread;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Blameable(on="create")
     *
     * @var User|null
     */
    protected ?User $author;

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
    protected ?string $content;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->content ?: '';
    }

    /**
     * @return Thread|null
     */
    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    /**
     * @param Thread|null $thread
     */
    public function setThread(?Thread $thread): void
    {
        $this->thread = $thread;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}
