<?php

declare(strict_types = 1);

namespace Everon\PackageGenerator;

use Everon\PackageGenerator\Configurator\PackagerConfigurator;

interface PackagerFacadeInterface
{
    /**
     * Specification:
     * - Require valid schema file to exist
     * - Load and validate schema
     * - Substitute schema variables
     * - Store processed data as configurator schema resource data
     * - Return updated configurator
     *
     * @param \Everon\PackageGenerator\Configurator\PackagerConfigurator $configurator
     *
     * @return \Everon\PackageGenerator\Configurator\PackagerConfigurator
     */
    public function buildSchema(PackagerConfigurator $configurator): PackagerConfigurator;

    /**
     * Specification:
     * - Require configurator schema resource data
     * - Generate resources based on schema and templates
     * - Return collection with meta information about generated files
     *
     * @param \Everon\PackageGenerator\Configurator\PackagerConfigurator $configurator
     *
     * @return \Everon\PackageGenerator\Configurator\GenerateResult[]
     */
    public function generate(PackagerConfigurator $configurator): array;
}
