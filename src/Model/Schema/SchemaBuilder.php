<?php

declare(strict_types = 1);

namespace Everon\PackageGenerator\Model\Schema;

use Everon\PackageGenerator\Model\Schema\SchemaScope;
use Everon\PackageGenerator\Model\Loader\SchemaLoader;
use Everon\PackageGenerator\Model\Schema\Resource;
use Everon\PackageGenerator\PackagerDefinitionInterface;
use Everon\PackageGenerator\Configurator\PackagerConfigurator;
use Everon\PackageGenerator\Configurator\SchemaConfigurator;

class SchemaBuilder
{
    protected SchemaLoader $loader;
    protected SchemaScope $scope;

    public function __construct(SchemaLoader $loader, SchemaScope $scope)
    {
        $this->loader = $loader;
        $this->scope = $scope;
    }

    public function buildSchema(PackagerConfigurator $transfer): PackagerConfigurator
    {
        $resources = [];
        $transfer = $this->loader->load($transfer);

        foreach ($transfer->requireSchema()->getSchemaData() as $resourceName => $resourceCollection) {
            foreach ($resourceCollection as $resourceItemCollection) {
                foreach ($resourceItemCollection as $resourceItem) {
                    $this->validate($transfer, $transfer->requireSchema(), $resourceName, $resourceItem);
                    $resourceItem = $this->scope->substitute($transfer->requireSchema()->requireScope(), $resourceItem);

                    $resources[$resourceName][] = (new Resource())->fromArray($resourceItem);
                }
            }
        }

        return $this->updateTransfer($transfer, $resources);
    }

    protected function updateTransfer(
        PackagerConfigurator $transfer,
        array $resources
    ): PackagerConfigurator {
        $transfer
            ->requireSchema()
            ->setResourceData($resources);

        return $transfer;
    }

    protected function validate(
        PackagerConfigurator $transfer,
        SchemaConfigurator $schema,
        string $resourceName,
        array $resourceItem
    ): void {
        if (!isset($resourceItem[PackagerDefinitionInterface::CONFIGURATION_SCHEMA_RESOURCE_LOCATION])) {
            throw new \LogicException(
                sprintf(
                    'Resource location for "%s" cannot be empty in "%s"',
                    $resourceName,
                    $transfer->getSchemaFilename()
                )
            );
        }
    }
}
