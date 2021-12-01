<?php

declare(strict_types = 1);

namespace Everon\PackageGenerator\Model\Schema;

use Everon\PackageGenerator\PackagerDefinitionInterface;

class Resource
{
    protected ?string $content;
    protected string $location;
    protected string $type = PackagerDefinitionInterface::CONFIGURATION_SCHEMA_RESOURCE_TYPE_FILE;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isDir(): bool
    {
        return $this->type === PackagerDefinitionInterface::CONFIGURATION_SCHEMA_RESOURCE_TYPE_DIR;
    }

    public function isFile(): bool
    {
        return $this->type === PackagerDefinitionInterface::CONFIGURATION_SCHEMA_RESOURCE_TYPE_FILE;
    }

    public function fromArray(array $data): self
    {
        $this->content = $data['content'] ?? null;
        $this->location = $data['location'] ?? '';
        $this->type = $data['type'] ?? PackagerDefinitionInterface::CONFIGURATION_SCHEMA_RESOURCE_TYPE_FILE;

        return $this;
    }
}
