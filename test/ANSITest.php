<?php

namespace mahlstrom\test;

use mahlstrom\ANSI;
use PHPUnit\Framework\TestCase;

/**
 * Class ANSITest
 * @package mahlstrom\test
 */
class ANSITest extends TestCase
{
    /**
     * @test
     */
    public function WithoutAnyArgumentsTheStringIsUnchanged()
    {
        $this->expectOutputString("Magnus\e[0m");
        echo ANSI::_('Magnus');
    }

    /**
     * @test
     */
    public function checkBasicColors()
    {
        $this->assertEquals("\e[30mtest\e[0m", ANSI::_("test")->black());
        $this->assertEquals("\e[31mtest\e[0m", ANSI::_("test")->red());
        $this->assertEquals("\e[32mtest\e[0m", ANSI::_("test")->green());
        $this->assertEquals("\e[33mtest\e[0m", ANSI::_("test")->yellow());
        $this->assertEquals("\e[34mtest\e[0m", ANSI::_("test")->blue());
        $this->assertEquals("\e[35mtest\e[0m", ANSI::_("test")->magenta());
        $this->assertEquals("\e[36mtest\e[0m", ANSI::_("test")->cyan());
        $this->assertEquals("\e[37mtest\e[0m", ANSI::_("test")->light_gray());
        $this->assertEquals("\e[39mtest\e[0m", ANSI::_("test")->default());
    }

    /**
     * @test
     */
    public function checkBasicBgColors()
    {
        $this->assertEquals("\e[40mtest\e[0m", ANSI::_("test")->bg_black());
        $this->assertEquals("\e[41mtest\e[0m", ANSI::_("test")->bg_red());
        $this->assertEquals("\e[42mtest\e[0m", ANSI::_("test")->bg_green());
        $this->assertEquals("\e[43mtest\e[0m", ANSI::_("test")->bg_yellow());
        $this->assertEquals("\e[44mtest\e[0m", ANSI::_("test")->bg_blue());
        $this->assertEquals("\e[45mtest\e[0m", ANSI::_("test")->bg_magenta());
        $this->assertEquals("\e[46mtest\e[0m", ANSI::_("test")->bg_cyan());
        $this->assertEquals("\e[47mtest\e[0m", ANSI::_("test")->bg_light_gray());
        $this->assertEquals("\e[49mtest\e[0m", ANSI::_("test")->bg_default());
    }

    /**
     * @test
     */
    public function testStripColors(){
        $this->assertEquals('Magnus', ANSI::stripColors(ANSI::_('Magnus')->bg_cyan()));
    }

    /**
     * @test
     */
    public function testInvoke(){
        $this->expectOutputString("Luna\e[0m");
        $Tobj=new ANSI('Magnus');
        $Tobj('Luna');
        echo $Tobj;
    }

    /**
     * @test
     */
    public function testGetSprintf(){
        $this->assertEquals('[30m[1mMagnus[0m                                  ', ANSI::getSprintf(ANSI::_('Magnus')->bold()->black(), 40));
    }
}
