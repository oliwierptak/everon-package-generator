<?php declare(strict_types = 1);

namespace Everon\PackageGenerator;

interface PackagerDefinitionInterface
{
    public const VERSION = '1.0.0';

    public const CONFIGURATION_SCHEMA_OPTION_SYMBOL = '$';

    public const CONFIGURATION_SCHEMA_RESOURCE_LOCATION = 'location';

    public const CONFIGURATION_SCHEMA_RESOURCE_TYPE_FILE = 'file';

    public const CONFIGURATION_SCHEMA_RESOURCE_TYPE_DIR = 'dir';
}
