<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EpisodeRepository")
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(columns={"content"}, flags={"fulltext"})
 *     }
 * )
 */
class Episode extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Story", inversedBy="episodes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Story|null
     */
    protected $story;

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
     * @return string
     */
    public function __toString(): string
    {
        return $this->content ?: '';
    }

    /**
     * @return Story|null
     */
    public function getStory(): ?Story
    {
        return $this->story;
    }

    /**
     * @param Story|null $story
     */
    public function setStory(?Story $story): void
    {
        $this->story = $story;
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
