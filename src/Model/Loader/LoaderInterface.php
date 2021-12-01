<?php

declare(strict_types = 1);

namespace Everon\PackageGenerator\Model\Loader;

use SplFileInfo;

interface LoaderInterface
{
    public function load(SplFileInfo $configurationFile): array;
}
