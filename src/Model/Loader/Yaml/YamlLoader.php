<?php

declare(strict_types = 1);

namespace Everon\PackageGenerator\Model\Loader\Yaml;

use Everon\PackageGenerator\Model\Loader\LoaderInterface;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class YamlLoader implements LoaderInterface
{
    public function load(SplFileInfo $configurationFile): array
    {
        return Yaml::parseFile(
            $configurationFile->getPathname(),
            Yaml::PARSE_OBJECT & Yaml::PARSE_CONSTANT & Yaml::PARSE_DATETIME & Yaml::PARSE_CUSTOM_TAGS
        );
    }
}
