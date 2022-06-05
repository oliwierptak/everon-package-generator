<?php

declare(strict_types = 1);

namespace Everon\PackageGenerator\Model\Loader;

use Everon\PackageGenerator\Model\Loader\FileLocator;
use Everon\PackageGenerator\Model\Loader\LoaderInterface;
use Everon\PackageGenerator\PackagerDefinitionInterface;
use Everon\PackageGenerator\Configurator\PackagerConfigurator;
use RuntimeException;
use SplFileInfo;
use function array_merge;

class SchemaLoader
{
    protected FileLocator $fileLocator;
    protected LoaderInterface $loader;

    public function __construct(FileLocator $fileLocator, LoaderInterface $loader)
    {
        $this->fileLocator = $fileLocator;
        $this->loader = $loader;
    }

    public function load(PackagerConfigurator $transfer): PackagerConfigurator
    {
        $configurationFile = $this->prepareSchemaFile($transfer);
        $data = $this->loader->load($configurationFile);

        $config = $this->extractConfig($data);
        $data = $this->removeOptionSymbol($data);

        $transfer
            ->requireSchema()
            ->setFilename($configurationFile->getPathname())
            ->setSchemaData($data)
            ->requireScope()->setData(
                $this->generateScopeValues($config, $transfer)
            );

        return $transfer;
    }

    protected function prepareSchemaFile(PackagerConfigurator $transfer): \SplFileInfo
    {
        $this->validate($transfer);

        return new SplFileInfo($transfer->getSchemaFilename());
    }

    protected function validate(PackagerConfigurator $transfer): void
    {
        $transfer->requireSchemaFilename();

        $this->validatePath($transfer->getSchemaFilename());
    }

    protected function validatePath(string $path): void
    {
        if (is_file($path) === false && is_dir($path) === false) {
            throw new RuntimeException(sprintf('SchemaConfigurator file not found under path: "%s"', $path));
        }
    }

    protected function extractConfig(array $data): array
    {
        return $data[PackagerDefinitionInterface::CONFIGURATION_SCHEMA_OPTION_SYMBOL] ?? [];
    }

    protected function removeOptionSymbol(array $data): array
    {
        unset($data[PackagerDefinitionInterface::CONFIGURATION_SCHEMA_OPTION_SYMBOL]);

        return $data;
    }

    protected function generateScopeValues(array $fileConfig, PackagerConfigurator $transfer): array
    {
        return array_merge(
            array_filter($fileConfig),
            array_filter($transfer->requireSchema()->requireScope()->getData())
        );
    }
}
