<?php

declare(strict_types = 1);

namespace Everon\PackageGenerator\Model\Schema;

use Everon\PackageGenerator\Configurator\GenerateResult;
use Everon\PackageGenerator\Configurator\PackagerConfigurator;
use Everon\PackageGenerator\Configurator\SchemaScopeConfigurator;
use Everon\PackageGenerator\PackagerDefinitionInterface;
use function dirname;
use function file_get_contents;
use function is_dir;
use function is_file;
use function mkdir;

class SchemaGenerator
{
    protected SchemaScope $scope;

    public function __construct(SchemaScope $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @param \Everon\PackageGenerator\Configurator\PackagerConfigurator $configurator
     *
     * @return \Everon\PackageGenerator\Configurator\GenerateResult[]
     */
    public function generate(PackagerConfigurator $configurator): array
    {
        $results = [];
        foreach ($configurator->requireSchema()->getResourceData() as $resourceName => $resourceCollection) {
            foreach ($resourceCollection as $resource) {
                $item = (new GenerateResult)
                    ->setResourceName($resourceName);

                $item = $this->generateResource(
                    $item,
                    $resource,
                    $configurator->requireSchema()->requireScope(),
                    $configurator->requireOutputPath(),
                    $configurator->shouldOverwriteExistingFiles()
                );

                $results[] = $item;
            }
        }

        return $results;
    }

    protected function generateResource(
        GenerateResult $item,
        Resource $resource,
        SchemaScopeConfigurator $scope,
        string $outputPath,
        bool $overwriteExistingFiles
    ): GenerateResult {
        $path = $this->generatePath($resource, $scope, $outputPath);
        $item->setPath($path);

        if ($resource->isDir()) {
            $this->createDirectory($path);
            $item->setType(PackagerDefinitionInterface::CONFIGURATION_SCHEMA_RESOURCE_TYPE_DIR);

            return $item;
        }

        if ($resource->isFile() && \file_exists($path) && $overwriteExistingFiles === false) {
            return $item;
        }

        $content = (string) $resource->getContent();
        if (is_file($content)) {
            $content = file_get_contents($content);
        }

        $content = current($this->scope->substitute($scope, [$content]));

        $this->createDirectory(dirname($path));

        $handle = fopen($path, 'w+');
        fwrite($handle, $content);
        fclose($handle);

        $item->setWasGenerated(true);

        return $item;
    }

    protected function generatePath(Resource $resource, SchemaScopeConfigurator $configurator, string $outputPath): string
    {
        return sprintf(
            '%s/%s',
            $outputPath,
            $resource->getLocation()
        );
    }

    protected function createDirectory(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
}
