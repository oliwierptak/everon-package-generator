<?php declare(strict_types = 1);

namespace %namespace%\Business;

use %namespace%\Business\Model\%name%Model;

class %name%BusinessFactory
{
    public function create%name%Model(): %name%Model
    {
        return new %name%Model();
    }
}
