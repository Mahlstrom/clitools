<?php
/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 2017-01-18
 * Time: 16:22
 */
namespace mahlstrom;

use ArrayIterator;

/**
 * @property string file
 * @property string path
 */
class FilePath extends ArrayIterator
{
    private $file = '';
    private $path = '';
    static private $root;

    /**
     * @return string
     */
    static public function getRoot()
    {
        if (!self::$root) {
            $bt = debug_backtrace();
            $b = array_pop($bt);
            $dir=pathinfo($b['file'])['dirname'];
            //$dir = __DIR__;
            $subs = count(explode('/', $dir));
            while ($subs > 0) {
                echo $dir . PHP_EOL;
                if (is_dir($dir . '/vendor') && is_file($dir . '/composer.json')) {
                    self::$root = $dir;
                    break;
                }
                $dir = realpath($dir . '/..');
                $subs--;
            }
        }
        return self::$root;
    }

    /**
     * FilePath constructor.
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        $this->path = '';
        $this[] = $basePath;
    }

    public function __get($name)
    {
        if ($name == 'file') {
            return $this->file;
        }
        if ($name == 'path') {
            return $this->getPath();
        }
    }

    public function __set($name, $value)
    {
        if ($name == 'file') {
            $this->file = $value;
        }
    }

    public function fileAppend($data)
    {
        $this->file = $this->__get('file') . $data;
    }

    public function __toString()
    {
        return join(DIRECTORY_SEPARATOR, $this->getArrayCopy()) . DIRECTORY_SEPARATOR . $this->file;
    }

    public function getPath()
    {
        return join(DIRECTORY_SEPARATOR, $this->getArrayCopy());
    }
}