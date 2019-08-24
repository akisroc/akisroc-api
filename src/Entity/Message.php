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
    public ?User $from = null;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="receivedMessages")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var User|null
     */
    public ?User $to = null;

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
