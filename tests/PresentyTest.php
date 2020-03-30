<?php

namespace Padosoft\Presenty\Test;

use PHPUnit\Framework\TestCase;

class PresentyTest extends TestCase
{
    protected function setUp():void
    {
    }

    protected function tearDown():void
    {
    }

    /**
     * @param $expected
     * @return bool
     */
    protected function expectedIsAnException($expected)
    {
        if (is_array($expected)) {
            return false;
        }

        return strpos($expected, 'Exception') !== false
        || strpos($expected, 'PHPUnit_Framework_') !== false
        || strpos($expected, 'TypeError') !== false;
    }

    /**
     * GetFavicon Tests.
     */
    public function test_prova()
    {
        $this->assertEquals("1", "1");
    }
}
