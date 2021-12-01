<?php declare(strict_types = 1);

namespace Test%namespace%\Business;

use %namespace%\Business\%name%Facade;
use PHPUnit\Framework\TestCase;

/**
 * @group functional
 */
class %name%FacadeTest extends TestCase
{
    public function test_example(): void
    {
        $facade = (new %name%Facade);

        $result = $facade->execute();

        $this->assertEquals('Lorem Ipsum %name%', $result);
    }
}
