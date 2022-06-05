<?php

namespace mahlstrom\test;

use mahlstrom\ANSI;
use PHPUnit\Framework\TestCase;

class ANSITest extends TestCase
{
    /**
     * @test
     */
    public function WithoutAnyArgumentsTheStringIsUnchanged()
    {
        $this->expectOutputString("Magnus");
        echo ANSI::_('Magnus');
    }

    public function testInstanceType(){
        $testObj=ANSI::_( 'test');
        $this->assertEquals('mahlstrom\ANSI',get_class($testObj));
    }

    /**
     * @test
     */
    public function testCodes(){
        $this->assertEquals("\e[1mtest\e[0m", ANSI::_("test")->bold());
        $this->assertEquals("\e[2mtest\e[0m", ANSI::_("test")->dark());
        $this->assertEquals("\e[3mtest\e[0m", ANSI::_("test")->italic());
        $this->assertEquals("\e[4mtest\e[0m", ANSI::_("test")->underline());
        $this->assertEquals("\e[5mtest\e[0m", ANSI::_("test")->blink());
        $this->assertEquals("\e[7mtest\e[0m", ANSI::_("test")->reverse());
        $this->assertEquals("\e[8mtest\e[0m", ANSI::_("test")->concealed());
//        $this->assertEquals("\e[9mtest\e[0m", ANSI::_("test")->default());
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
        $this->assertEquals("\e[35mtest\e[0m", ANSI::_("test")->purple());
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
    public function checkExtendedColors()
    {
        $this->assertEquals("\e[90mtest\e[0m", ANSI::_("test")->dark_gray());
        $this->assertEquals("\e[91mtest\e[0m", ANSI::_("test")->light_red());
        $this->assertEquals("\e[92mtest\e[0m", ANSI::_("test")->light_green());
        $this->assertEquals("\e[93mtest\e[0m", ANSI::_("test")->light_yellow());
        $this->assertEquals("\e[94mtest\e[0m", ANSI::_("test")->light_blue());
        $this->assertEquals("\e[95mtest\e[0m", ANSI::_("test")->light_magenta());
        $this->assertEquals("\e[95mtest\e[0m", ANSI::_("test")->light_purple());
        $this->assertEquals("\e[96mtest\e[0m", ANSI::_("test")->light_cyan());
        $this->assertEquals("\e[97mtest\e[0m", ANSI::_("test")->white());
    }

    /**
     * @test
     */
    public function checkExtendedBgColors()
    {
        $this->assertEquals("\e[100mtest\e[0m", ANSI::_("test")->bg_dark_gray());
        $this->assertEquals("\e[101mtest\e[0m", ANSI::_("test")->bg_light_red());
        $this->assertEquals("\e[102mtest\e[0m", ANSI::_("test")->bg_light_green());
        $this->assertEquals("\e[103mtest\e[0m", ANSI::_("test")->bg_light_yellow());
        $this->assertEquals("\e[104mtest\e[0m", ANSI::_("test")->bg_light_blue());
        $this->assertEquals("\e[105mtest\e[0m", ANSI::_("test")->bg_light_magenta());
        $this->assertEquals("\e[106mtest\e[0m", ANSI::_("test")->bg_light_cyan());
        $this->assertEquals("\e[107mtest\e[0m", ANSI::_("test")->bg_white());
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
        $this->expectOutputString("Luna");
        $Tobj=new ANSI('Magnus');
        $Tobj('Luna');
        echo $Tobj;
    }

    /**
     * @test
     */
    public function testGetSprintf(){
        $this->assertEquals('[1;30mMagnus[0m                                  ', ANSI::getSprintf(ANSI::_('Magnus')->bold()->black(), 40));
    }

    public function testFg(){
        $this->expectOutputString("\e[38;5;231mMagnus\e[39m");
        echo ANSI::_('Magnus')->fg("#FFFFFF");
    }
    public function testBg(){
        $this->expectOutputString("\e[48;5;231mMagnus\e[49m");
        echo ANSI::_('Magnus')->bg("#FFFFFF");
    }
    public function testFgBg(){
        $this->expectOutputString("\e[38;5;16;48;5;231mMagnus\e[0m");
        echo ANSI::_('Magnus')->fg("#000000")->bg("#FFFFFF");
    }
}
