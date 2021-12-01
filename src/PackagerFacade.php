<?php declare(strict_types = 1);

namespace Everon\PackageGenerator;

use Everon\PackageGenerator\Configurator\PackagerConfigurator;

class PackagerFacade implements PackagerFacadeInterface
{
    protected PackagerFactory $factory;

    protected function getFactory(): PackagerFactory
    {
        if (empty($this->factory)) {
            $this->factory = new PackagerFactory();
        }
        
        return $this->factory;
    }

    public function buildSchema(PackagerConfigurator $configurator): PackagerConfigurator
    {
        return $this
            ->getFactory()
            ->createSchemaBuilderModel()
            ->buildSchema($configurator);
    }

    public function generate(PackagerConfigurator $configurator): array
    {
        return $this
            ->getFactory()
            ->createSchemaGeneratorModel()
            ->generate($configurator);
    }
}
