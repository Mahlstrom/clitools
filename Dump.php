<?php
/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 2017-02-01
 * Time: 13:02
 */
namespace mahlstrom;

class Dump
{
    private $tSize = 2;
    private $indent = -2;

    public static function _($i)
    {
        new Dump($i);
    }

    public function __invoke($data)
    {
        $this->doCheck($data);
    }

    public function __construct($data = false, $indent = 4)
    {
        $this->tSize = $indent;
        $this->indent = -$this->tSize;
        if ($data !== null) {
            $this->doCheck($data);
        }
    }

    private function doCheck($i)
    {
        $this->indent += $this->tSize;
        switch (gettype($i)) {
            case 'NULL':
                echo C::_('NULL' . PHP_EOL)->cyan();
                break;
            case 'boolean':
                echo C::_(($i) ? "true" : "false")->cyan();
                echo PHP_EOL;
                break;
            case 'string':
                echo '"' . C::_($i)->yellow()->dark() . '"' . C::_(' (' . mb_strlen($i) . ')')->dark_gray();
                echo PHP_EOL;
                break;
            case 'integer':
                echo C::_($i)->light_blue();
                echo PHP_EOL;
                break;
            case 'array':
                $this->put('array {', -$this->indent);
                $this->doArray($i);
                break;
            case 'object':
                $className = get_class($i);
                $this->put($className . ' {', false);
                echo PHP_EOL;
                $vars = (array)$i;
                $vw = $this->checkVarWidth($vars);
                foreach ($vars as $varKey => $var) {
                    if (preg_match('/^\x00' . str_replace('\\', '\\\\', $className) . '\x00(.*)$/', $varKey, $res)) {
                        $keyOut = C::_($res[1]);
                        $dd = C::_('=>')->red()->dark();
                    } elseif (preg_match('/^\x00\*\x00(.*)$/', $varKey, $res)) {
                        $keyOut = C::_($res[1]);
                        $dd = C::_('=>')->cyan();
                    } else {
                        $keyOut = C::_($varKey);
                        $dd = C::_('=>')->light_green();
                    }
                    $kSize = mb_strlen(C::stripColors($keyOut));
                    $kSpace = $vw - $kSize + 2;
                    if (($vw - $kSize) < 0) {
                        echo PHP_EOL;
                        echo 'K: ' . $keyOut->__toString() . PHP_EOL;
                        echo 'M: ' . $vw . PHP_EOL;
                        echo 'S: ' . $kSize . PHP_EOL;
                        $this->checkVarWidth($vars, true);
                        exit();
                    }
                    if ($kSpace < 0) {
                        $kSpace = 0;
                    }
                    $this->put("[" . $keyOut . "]" . str_repeat(" ", $kSpace) . " $dd ", $this->tSize);
                    $this->doCheck($var);
                }
                echo PHP_EOL;
                $this->put('}' . PHP_EOL);
                break;
            default:
                $this->put(gettype($i) . PHP_EOL, -$this->indent);
                echo PHP_EOL;
                echo C::_('FIX THIS')->red()->underline();
                exit();
        }
        $this->indent -= $this->tSize;
    }

    private function put($str, $extra = 0)
    {
        if ($extra !== false) {
            echo str_repeat(' ', ($this->indent + $extra));
        }
        echo $str;
    }

    private function checkVarWidth(array $vars, bool $debug = false)
    {
        $ret = 0;
        foreach ($vars as $k => $v) {
            $k = preg_replace('/^\x00\*\x00(.*)$/', '$1', $k);
            $k = preg_replace('/^\x00.*\x00(.*)$/', '$1', $k);
            if (mb_strlen($k) > $ret) {
                $ret = mb_strlen($k);
            }
        }
        return $ret;
    }

    /**
     * @param $i
     */
    private function doArray($i)
    {
        if (count($i)) {
            echo PHP_EOL;
            $vw = $this->checkVarWidth($i);
            foreach ($i as $k => $v) {
                $kSize = mb_strlen($k);
                $this->put('[' . $k . ']' . str_repeat(' ', $vw - $kSize) . ' => ', $this->tSize);
                $this->doCheck($v);
            }
            $this->put('}' . PHP_EOL);
        } else {
            $this->put('}' . PHP_EOL, false);
        }
    }
}