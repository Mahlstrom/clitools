<?php
/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 2017-01-18
 * Time: 12:24
 */
namespace mahlstrom;
/**
 * Class C.
 *
 * @method $this bold()
 * @method $this dark()
 * @method $this italic()
 * @method $this underline()
 * @method $this blink()
 * @method $this reverse()
 * @method $this concealed()

 * @method $this default()
 * @method $this black()
 * @method $this red()
 * @method $this green()
 * @method $this yellow()
 * @method $this blue()
 * @method $this magenta()
 * @method $this cyan()
 * @method $this light_gray()

 * @method $this purple()
 * @method $this light_purple()

 * @method $this dark_gray()
 * @method $this light_red()
 * @method $this light_green()
 * @method $this light_yellow()
 * @method $this light_blue()
 * @method $this light_magenta()
 * @method $this light_cyan()
 * @method $this white()

 * @method $this bg_default()
 * @method $this bg_black()
 * @method $this bg_red()
 * @method $this bg_green()
 * @method $this bg_yellow()
 * @method $this bg_blue()
 * @method $this bg_magenta()
 * @method $this bg_cyan()
 * @method $this bg_light_gray()

 * @method $this bg_dark_gray()
 * @method $this bg_light_red()
 * @method $this bg_light_green()
 * @method $this bg_light_yellow()
 * @method $this bg_light_blue()
 * @method $this bg_light_magenta()
 * @method $this bg_light_cyan()
 * @method $this bg_white()
 *
 */
class C
{
    private $string = '';
    private $composed;

    private $fg=0;
    private $bg=0;
    private $extra=[];
    protected $styles = array(
        'reset'            => '0',
        'bold'             => '1',
        'dark'             => '2',
        'italic'           => '3',
        'underline'        => '4',
        'blink'            => '5',
        'reverse'          => '7',
        'concealed'        => '8',

        'default'          => '39',
        'black'            => '30',
        'red'              => '31',
        'green'            => '32',
        'yellow'           => '33',
        'blue'             => '34',
        'magenta'          => '35',
        'cyan'             => '36',
        'light_gray'       => '37',

        'dark_gray'        => '90',
        'light_red'        => '91',
        'light_green'      => '92',
        'light_yellow'     => '93',
        'light_blue'       => '94',
        'light_magenta'    => '95',
        'light_cyan'       => '96',
        'white'            => '97',

        'bg_default'       => '49',
        'bg_black'         => '40',
        'bg_red'           => '41',
        'bg_green'         => '42',
        'bg_yellow'        => '43',
        'bg_blue'          => '44',
        'bg_magenta'       => '45',
        'bg_cyan'          => '46',
        'bg_light_gray'    => '47',

        'bg_dark_gray'     => '100',
        'bg_light_red'     => '101',
        'bg_light_green'   => '102',
        'bg_light_yellow'  => '103',
        'bg_light_blue'    => '104',
        'bg_light_magenta' => '105',
        'bg_light_cyan'    => '106',
        'bg_white'         => '107',
    );
    static public function _($string)
    {
        return new C($string);
    }

    public function __construct($string=false)
    {
        $this->string = $string;
    }
    public function __invoke($string)
    {
        $this->string=$string;
        return $this;
    }

    public function __call($name, $arguments)
    {
        $name=preg_replace('/purple/','magenta',$name);
        $this->string="\033[".$this->styles[$name].'m'.$this->string;
        return $this;
    }

    public function __toString()
    {
        return $this->string ."\033[0m";
    }

    public static function getSprintf($string, $size)
    {
        $added = 0;
        if (preg_match_all("/\\033\[[^\m]*m/", $string, $ar)) {
            $added = strlen($string) - strlen(preg_replace('/\\033\[[^\m]*m/', '', $string));
        }
        $string=sprintf('%-' . ($added + $size) . 's', $string);
        return $string;
    }
    public static function stripColors($string){
        return preg_replace('/\\033\[[^\m]*m/', '', $string);
    }


}