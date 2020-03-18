<?php

declare(strict_types=1);

namespace App\Utils\Menu;

/**
 * Class Menu
 * @package App\Utils\Menu
 */
final class Menu implements \JsonSerializable
{
    /** @var Node $rootNode */
    private Node $rootNode;

    private ?string $title;

    /**
     * Menu constructor.
     * @param string|null $title
     */
    public function __construct(?string $title = null)
    {
        $this->rootNode = new Node();
        $this->title = $title;
    }

    /**
     * @param Node $node
     * @return self
     */
    public function addNode(Node $node): self
    {
        $this->rootNode->addNode($node);

        return $this;
    }

    /**
     * @return Node[]
     */
    public function getNodes(): array
    {
        return $this->rootNode->getNodes();
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->rootNode->jsonSerialize();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->rootNode->toArray();
    }
}
