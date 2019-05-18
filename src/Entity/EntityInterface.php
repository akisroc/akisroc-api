<?php

namespace App\Entity;

/**
 * Interface EntityInterface
 * @package App\Entity
 */
interface EntityInterface
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @param string|null $id
     * @return void
     */
    public function setId(?string $id): void;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime;

    /**
     * @param \DateTime|null $createdAt
     * @return void
     */
    public function setCreatedAt(?\DateTime $createdAt): void;

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime;

    /**
     * @param \DateTime|null $updatedAt
     * @return void
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void;
}
