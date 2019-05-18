<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Todo: Cannot send message to self
 *
 * @ORM\Entity()
 */
class Message extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sentMessages")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Blameable(on="create")
     *
     * @var User|null
     */
    protected $from;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="receivedMessages")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var User|null
     */
    protected $to;

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
     * @return User|null
     */
    public function getFrom(): ?User
    {
        return $this->from;
    }

    /**
     * @param User|null $from
     */
    public function setFrom(?User $from): void
    {
        $this->from = $from;
    }

    /**
     * @return User|null
     */
    public function getTo(): ?User
    {
        return $this->to;
    }

    /**
     * @param User|null $to
     */
    public function setTo(?User $to): void
    {
        $this->to = $to;
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
