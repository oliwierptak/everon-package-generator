<?php declare(strict_types = 1);

namespace %namespace%\Business;

use %vendor%\Shared\Transfer\%name%\%name%Transfer;

class %name%Facade
{
    protected %name%BusinessFactory $factory;

    protected function getFactory(): %name%BusinessFactory
    {
        if (empty($this->factory)) {
            $this->factory = new %name%BusinessFactory();
        }

        return $this->factory;
    }

    public function execute(): string
    {
        return $this
            ->getFactory()
            ->create%name%Model()
            ->execute();
    }
}
