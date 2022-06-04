<?php
/**
 * @author Magnus Ahlstrom <magnus@atuin.se>
 * @time 2015-01-19 22:49
 */

namespace mahlstrom\test;

use InvalidArgumentException;
use mahlstrom\CommandLineArg;
use PHPUnit\Framework\TestCase;

/**
 * Class CommandLineArgTest
 */
class CommandLineArgTest extends TestCase
{

    protected string $expStr;

    /**
     * @test
     */
    public function weShouldBeAbleToRunWithoutAnyArgumentsOrRequiredFlags()
    {
        $this->expectOutputString('');
        CommandLineArg::reset();
        CommandLineArg::addArgument('ano', 'a', 'Description');
        CommandLineArg::parse(['']);
    }

    /**
     * @test
     */
    public function testAllGoodArguments()
    {
        $arguments = [
            '',
            '-a',
            '-b',
            '-c',
            'cc',
            '-e',
            '-f',
            'ff',
            '-d',
        ];
        CommandLineArg::parse($arguments);
        $this->assertTrue(CommandLineArg::get('ano'));
        $this->assertTrue(CommandLineArg::get('bnn'));
        $this->assertEquals('cc', CommandLineArg::get('cny'));
        $this->assertTrue(CommandLineArg::get('dyo'));
        $this->assertTrue(CommandLineArg::get('eyn'));
        $this->assertEquals('ff', CommandLineArg::get('fyy'));
    }

    /**
     * @test
     */
    public function testAllDoubleDashes()
    {
        $arguments = [
            '',
            '--ano=aa',
            '--bnn',
            '--cny=cc',
            '--dyo=dd',
            '--eyn',
            '--fyy=ff'
        ];
        CommandLineArg::parse($arguments);
        $this->assertEquals('aa', CommandLineArg::get('ano'));
        $this->assertTrue(CommandLineArg::get('bnn'));
        $this->assertEquals('cc', CommandLineArg::get('cny'));
        $this->assertEquals('dd', CommandLineArg::get('dyo'));
        $this->assertTrue(CommandLineArg::get('eyn'));
        $this->assertEquals('ff', CommandLineArg::get('fyy'));
    }

    /**
     * @test
     */
    public function globalArgs()
    {
        $this->expectException(InvalidArgumentException::class);
       // $this->expectExceptionMessageMatches('configuration is not a valid argument');
        CommandLineArg::parse();
    }

    /**
     * @test
     */
    public function showHelp()
    {
        $this->expectOutputString($this->expStr);
        CommandLineArg::parse(['', '-h']);
        CommandLineArg::reset();
    }

    /**
     * @test
     */
    public function checkInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        CommandLineArg::reset();
        CommandLineArg::parse(['', '-wrong']);
    }

    /**
     * @test
     */
    public function doRequiredFail()
    {
        $expStr = 'dyo is required' . PHP_EOL;
        $expStr .= 'eyn is required' . PHP_EOL;
        $expStr .= 'fyy is required' . PHP_EOL;
        $expStr .= $this->expStr;
        $this->expectOutputString($expStr);
        CommandLineArg::parse(['', '-a']);
    }

    /**
     * @test
     */
    public function doRequiredValueFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cny must have value.');
        $arguments = [
            '',
            '-a',
            '-b',
            '-c',
            '-f',
            'ff',
            '-e',
            '-d',
        ];
        CommandLineArg::parse($arguments);
    }

    /**
     * @test
     */
    public function doNoRequiredValue()
    {
        $expStr = 'bnn needs no argument' . PHP_EOL;
        $expStr .= 'eyn needs no argument' . PHP_EOL;
        $this->expectOutputString($expStr);
        $arguments = [
            '',
            '-a',
            'argument',
            '-b',
            'argument',
            '-c',
            'argument',
            '-d',
            'argument',
            '-e',
            'argument',
            '-f',
            'ff'
        ];
        CommandLineArg::parse($arguments);
    }

    /**
     * @test
     */
    public function tryGetWrongValue()
    {
        $this->assertFalse(CommandLineArg::get('fea'));
    }

    /**
     * @test
     */
    public function testMultipleChars()
    {
        $this->expectOutputString('');
        CommandLineArg::reset();
        CommandLineArg::addArgument('ano', 'a', 'Description');
        CommandLineArg::addArgument('bnn', 'b', 'Description');
        CommandLineArg::addArgument('cny', 'c', 'Description');
        CommandLineArg::parse(['', '-abc', 'magnus']);
        $this->assertTrue(CommandLineArg::get('ano'));
        $this->assertEquals('magnus', CommandLineArg::get('cny'));
    }

    /**
     * @test
     */
    public function testMultipleCharsWithOneForcedValue()
    {
        $this->expectOutputString('');
        CommandLineArg::reset();
        CommandLineArg::addArgument('ano', 'a', 'Description');
        CommandLineArg::addArgument('bnn', 'b', 'Description');
        CommandLineArg::addArgument('cny', 'c', 'Description', false, true);
        CommandLineArg::addArgument('dyo', 'd', 'Description', true);
        CommandLineArg::addArgument('eyn', 'e', 'Description', true, false);
        CommandLineArg::addArgument('fyy', 'f', 'Description', true, true);
        CommandLineArg::parse(['', '-abc', 'magnus', '-def', 'fa']);
        $this->assertTrue(CommandLineArg::get('ano'));
        $this->assertEquals('magnus', CommandLineArg::get('cny'));
    }

    /**
     * @test
     */
    public function testMultipleCharsWithOneForcedValueWrongOrder()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cny must have value.');
        $this->expectOutputString('');
        CommandLineArg::reset();
        CommandLineArg::addArgument('ano', 'a', 'Description');
        CommandLineArg::addArgument('bnn', 'b', 'Description');
        CommandLineArg::addArgument('cny', 'c', 'Description', false, true);
        CommandLineArg::parse(['', '-acb', 'magnus']);
        $this->assertTrue(CommandLineArg::get('ano'));
        $this->assertEquals('magnus', CommandLineArg::get('cny'));
    }

    /**
     * @test
     */
    public function haveArgumentsWithAndWithoutFlags()
    {
        $this->expectOutputString('');
        CommandLineArg::reset();
        CommandLineArg::addArgument('ano', 'a', 'Description');
        CommandLineArg::addArgument('bnn', 'b', 'Description');
        CommandLineArg::parse(['', 'Arg1', '-a', 'FlagArg']);
        $this->assertEquals('FlagArg', CommandLineArg::get('ano'));
        $this->assertEquals('Arg1', CommandLineArg::getArgs()[0]);
        CommandLineArg::reset();
        CommandLineArg::addArgument('ano', 'a', 'Description');
        CommandLineArg::addArgument('bnn', 'b', 'Description');
        CommandLineArg::parse(['', '-a', 'FlagArg', 'Arg1']);
        $this->assertEquals('FlagArg', CommandLineArg::get('ano'));
        $this->assertEquals('Arg1', CommandLineArg::getArgs()[0]);
    }

    protected function setUp(): void
    {
        $this->expStr = 'Usage: phpunit [OPTIONS]... ' . PHP_EOL;
        $this->expStr .= '  -a          --ano                       Description' . PHP_EOL;
        $this->expStr .= '  -b [<arg>]  --bnn[=<arg>]               Description' . PHP_EOL;
        $this->expStr .= '  -c <arg>    --cny=<arg>                 Description' . PHP_EOL;
        $this->expStr .= '  -d          --dyo                       Description (required)' . PHP_EOL;
        $this->expStr .= '  -e [<arg>]  --eyn[=<arg>]               Description (required)' . PHP_EOL;
        $this->expStr .= '  -f <arg>    --fyy=<arg>                 Description (required)' . PHP_EOL;
        CommandLineArg::reset();
        CommandLineArg::addArgument('ano', 'a', 'Description');
        CommandLineArg::addArgument('bnn', 'b', 'Description', false, false);
        CommandLineArg::addArgument('cny', 'c', 'Description', false, true);
        CommandLineArg::addArgument('dyo', 'd', 'Description', true);
        CommandLineArg::addArgument('eyn', 'e', 'Description', true, false);
        CommandLineArg::addArgument('fyy', 'f', 'Description', true, true);
    }
}
