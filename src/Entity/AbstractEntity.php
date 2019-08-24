<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractEntity
 * @package App\Entity
 */
abstract class AbstractEntity implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     *
     * @var string|null
     */
    public ?string $id = null;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @Assert\DateTime()
     *
     * @var \DateTime|null
     */
    public ?\DateTime $createdAt = null;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @Assert\DateTime()
     *
     * @var \DateTime|null
     */
    public ?\DateTime $updatedAt = null;

}
