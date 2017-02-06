<?php
/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 2017-01-26
 * Time: 23:19
 */
namespace mahlstrom\cli;

use DateTime;
use mahlstrom\C;

/**
 * Class CLIHelper
 */
class Factory
{
    private static $lines = 0;
    private static $buff = "";
    private static $w;
    private static $h;

    /**
     * @return array
     */
    public static function getHeightWidth()
    {
        self::$w = exec('tput cols');
        self::$h = exec('tput lines');
        return [self::$h, self::$w];
    }

    /**
     * @param integer|boolean $roc
     */
    public static function fillRest($roc = false)
    {
        if (!$roc) {
            $roc = self::$lines;
        }
        for ($i = 0; $i < (self::$h - $roc - 1); $i++) {
            self::$buff .= C::_(str_repeat(' ', self::$w)) . PHP_EOL;
        }
        $D = (new DateTime())->format('Y-m-d H:i:s');
        self::$buff .= C::_(sprintf('%-' . (self::$w - strlen($D)) . 's%s', getmypid(), $D));
        echo self::$buff;
    }

    /**
     * @param string|array|\mahlstrom\C $input
     * @param boolean                   $center
     * @param boolean                   $return
     * @return string
     */
    public static function fLine($input, bool $center = false, bool $return = false)
    {
        if (is_array($input)) {
            foreach ($input as $string) {
                self::storeOrReturn($string, $center, $return);
            }
        } else {
            return self::storeOrReturn($input, $center, $return);
        }
        return false;
    }

    /**
     * @param string  $string
     * @param boolean $clear
     */
    public static function init(string $string, bool $clear = true)
    {
        self::$w = exec('tput cols');
        self::$h = exec('tput lines');
        self::$lines = 0;
        if ($clear) {
            self::$buff = "\033[H";
        }
        self::$buff .= C::_(self::fLine(" $string ", true, true))->white()->underline()->bold();
    }

    /**
     * @param string|\mahlstrom\C $string
     * @param boolean             $center
     * @param boolean             $return
     * @return string
     */
    public static function storeOrReturn($string, bool $center, bool $return)
    {
        self::$lines++;
        $len = strlen(C::stripColors($string));
        $dif = (strlen($string) - $len);
        if ($center) {
            $sides = (self::$w / 2) - $len / 2;
            $completeLine = sprintf('%' . ceil($sides) . 's%' . $len . 's%' . floor($sides) . 's', '', $string, '');
        } else {
            $completeLine = sprintf('%-' . (self::getHeightWidth()[1] + $dif) . 's', $string);
        }
        if ($return) {
            return $completeLine . PHP_EOL;
        } else {
            self::$buff .= $completeLine . PHP_EOL;
        }
        return '';
    }
}
