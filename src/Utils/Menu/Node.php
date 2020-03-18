<?php

declare(strict_types=1);

namespace App\Utils\Menu;

/**
 * Class Node
 * @package App\Utils\Menu
 */
final class Node implements \JsonSerializable
{
    /** @var string|null $href */
    private ?string $href;

    /** @var string|null $label */
    private ?string $label;

    /** @var Node[] */
    private array $nodes;

    /**
     * Node constructor.
     * @param string|null $href
     * @param string|null $label
     * @param Node ...$nodes
     */
    public function __construct(
        ?string $href = null,
        ?string $label = null,
        Node ...$nodes
    ) {
        $this->href = $href;
        $this->label = $label;
        $this->nodes = $nodes;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @param string|null $href
     * @return self
     */
    public function setHref(?string $href): self
    {
        $this->href = $href;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     * @return self
     */
    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param Node $node
     * @return self
     */
    public function addNode(Node $node): self
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * @return Node[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return json_encode(
            $this->toArray(),
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        $array['href'] = $this->href;
        $array['label'] = $this->label;
        $array['nodes'] = [];

        foreach ($this->nodes as $node) {
            $array['nodes'][] = $node->toArray();
        }

        return $array;
    }
}
