<?php

declare(strict_types = 1);

namespace Everon\PackageGenerator\Model\Schema;

use Everon\PackageGenerator\Configurator\SchemaScopeConfigurator;
use function array_keys;
use function array_values;
use function array_walk;
use function array_walk_recursive;
use function sprintf;
use function str_ireplace;

class SchemaScope
{
    public function substitute(SchemaScopeConfigurator $configurator, array $data): array
    {
        $expressions = array_keys($configurator->getData());
        $values = array_values($configurator->getData());

        array_walk_recursive($data, function (&$item) use ($expressions, $values) {
            array_walk($expressions, function (&$expressionItem) {
                $expressionItem = sprintf('%%%s%%', $expressionItem);
            });

            $item = str_ireplace($expressions, $values, $item);
        });

        return $data;
    }
}
