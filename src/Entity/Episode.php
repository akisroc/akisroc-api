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
    public ?Story $story = null;

    /**
     * @ORM\ManyToOne(targetEntity="Protagonist", inversedBy="posts")
     * @ORM\JoinColumn(nullable=true)
     *
     * @var Protagonist|null
     */
    public ?Protagonist $protagonist = null;

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
