<?php

namespace mahlstrom\cli;

use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{

    /**
     * @test
     */
    public function testGetHeightWidth()
    {
        $res=Factory::getHeightWidth();
        $this->assertIsArray($res);
        $this->assertIsString($res[0]);
        $this->assertIsString($res[1]);
        $this->assertCount(2,$res);
    }
}
