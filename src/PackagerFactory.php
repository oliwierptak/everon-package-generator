<?php declare(strict_types = 1);

namespace Everon\PackageGenerator;

use Everon\PackageGenerator\Model\Loader\FileLocator;
use Everon\PackageGenerator\Model\Loader\SchemaLoader;
use Everon\PackageGenerator\Model\Loader\Yaml\YamlLoader;
use Everon\PackageGenerator\Model\Schema\SchemaBuilder;
use Everon\PackageGenerator\Model\Schema\SchemaGenerator;
use Everon\PackageGenerator\Model\Schema\SchemaScope;
use Symfony\Component\Finder\Finder;

class PackagerFactory
{
    public function createSchemaGeneratorModel(): SchemaGenerator
    {
        return new SchemaGenerator(
            $this->createSchemaScope()
        );
    }

    public function createSchemaBuilderModel(): SchemaBuilder
    {
        return new SchemaBuilder(
            $this->createSchemaLoader(),
            $this->createSchemaScope()
        );
    }

    protected function createSchemaLoader(): SchemaLoader
    {
        return new SchemaLoader(
            $this->createFileLocator(),
            $this->createLoader()
        );
    }

    protected function createFileLocator(): FileLocator
    {
        return new FileLocator(Finder::create());
    }

    protected function createLoader(): YamlLoader
    {
        return new YamlLoader();
    }

    protected function createSchemaScope(): SchemaScope
    {
        return new SchemaScope();
    }
}
