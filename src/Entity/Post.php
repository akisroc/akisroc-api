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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Blameable(on="create")
     *
     * @var User|null
     */
    protected $author;

    /**
     * @ORM\ManyToOne(targetEntity="Protagonist", inversedBy="posts")
     * @ORM\JoinColumn(nullable=true)
     *
     * @var Protagonist|null
     */
    protected $protagonist;

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
    protected $content;

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
     * @return Protagonist|null
     */
    public function getProtagonist(): ?Protagonist
    {
        return $this->protagonist;
    }

    /**
     * @param Protagonist|null $protagonist
     */
    public function setProtagonist(?Protagonist $protagonist): void
    {
        $this->protagonist = $protagonist;
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
