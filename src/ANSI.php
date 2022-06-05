<?php
namespace mahlstrom;
/**
 * Class ANSI.
 *
 * @method $this fg(string|int $color) # Either webHex or bash 256 color
 * @method $this bg(string|int $color)
 * @method $this pos(int $Row, int $Col) # Row and Col starts at 1
 * @method $this width(int $width)
 * @method $this padding(int $padding)
 * @method $this bold()
 * @method $this dark()
 * @method $this italic()
 * @method $this underline()
 * @method $this blink()
 * @method $this reverse()
 * @method $this concealed()
 * @method $this strike()
 * @method $this setText(string $text);
 * @method $this align_center()
 * @method $this align_left()
 * @method $this align_right()

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
class ANSI
{
    public string $string;
    protected $styles = array(
        'reset' => '0',
        'bold' => '1',
        'dark' => '2',
        'italic' => '3',
        'underline' => '4',
        'blink' => '5',
        'reverse' => '7',
        'concealed' => '8',
        'strike' => '9',

        'default' => '39',
        'black' => '30',
        'red' => '31',
        'green' => '32',
        'yellow' => '33',
        'blue' => '34',
        'magenta' => '35',
        'cyan' => '36',
        'light_gray' => '37',

        'dark_gray' => '90',
        'light_red' => '91',
        'light_green' => '92',
        'light_yellow' => '93',
        'light_blue' => '94',
        'light_magenta' => '95',
        'light_cyan' => '96',
        'white' => '97',

        'bg_default' => '49',
        'bg_black' => '40',
        'bg_red' => '41',
        'bg_green' => '42',
        'bg_yellow' => '43',
        'bg_blue' => '44',
        'bg_magenta' => '45',
        'bg_cyan' => '46',
        'bg_light_gray' => '47',

        'bg_dark_gray' => '100',
        'bg_light_red' => '101',
        'bg_light_green' => '102',
        'bg_light_yellow' => '103',
        'bg_light_blue' => '104',
        'bg_light_magenta' => '105',
        'bg_light_cyan' => '106',
        'bg_white' => '107',
    );

    private int $bg;
    private int $fg;
    private array $webTo256 = [
        '000000',
        '800000',
        '008000',
        '808000',
        '000080',
        '800080',
        '008080',
        'c0c0c0',
        '808080',
        'ff0000',
        '00ff00',
        'ffff00',
        '0000ff',
        'ff00ff',
        '00ffff',
        'ffffff',
        '000000',
        '00005f',
        '000087',
        '0000af',
        '0000d7',
        '0000ff',
        '005f00',
        '005f5f',
        '005f87',
        '005faf',
        '005fd7',
        '005fff',
        '008700',
        '00875f',
        '008787',
        '0087af',
        '0087d7',
        '0087ff',
        '00af00',
        '00af5f',
        '00af87',
        '00afaf',
        '00afd7',
        '00afff',
        '00d700',
        '00d75f',
        '00d787',
        '00d7af',
        '00d7d7',
        '00d7ff',
        '00ff00',
        '00ff5f',
        '00ff87',
        '00ffaf',
        '00ffd7',
        '00ffff',
        '5f0000',
        '5f005f',
        '5f0087',
        '5f00af',
        '5f00d7',
        '5f00ff',
        '5f5f00',
        '5f5f5f',
        '5f5f87',
        '5f5faf',
        '5f5fd7',
        '5f5fff',
        '5f8700',
        '5f875f',
        '5f8787',
        '5f87af',
        '5f87d7',
        '5f87ff',
        '5faf00',
        '5faf5f',
        '5faf87',
        '5fafaf',
        '5fafd7',
        '5fafff',
        '5fd700',
        '5fd75f',
        '5fd787',
        '5fd7af',
        '5fd7d7',
        '5fd7ff',
        '5fff00',
        '5fff5f',
        '5fff87',
        '5fffaf',
        '5fffd7',
        '5fffff',
        '870000',
        '87005f',
        '870087',
        '8700af',
        '8700d7',
        '8700ff',
        '875f00',
        '875f5f',
        '875f87',
        '875faf',
        '875fd7',
        '875fff',
        '878700',
        '87875f',
        '878787',
        '8787af',
        '8787d7',
        '8787ff',
        '87af00',
        '87af5f',
        '87af87',
        '87afaf',
        '87afd7',
        '87afff',
        '87d700',
        '87d75f',
        '87d787',
        '87d7af',
        '87d7d7',
        '87d7ff',
        '87ff00',
        '87ff5f',
        '87ff87',
        '87ffaf',
        '87ffd7',
        '87ffff',
        'af0000',
        'af005f',
        'af0087',
        'af00af',
        'af00d7',
        'af00ff',
        'af5f00',
        'af5f5f',
        'af5f87',
        'af5faf',
        'af5fd7',
        'af5fff',
        'af8700',
        'af875f',
        'af8787',
        'af87af',
        'af87d7',
        'af87ff',
        'afaf00',
        'afaf5f',
        'afaf87',
        'afafaf',
        'afafd7',
        'afafff',
        'afd700',
        'afd75f',
        'afd787',
        'afd7af',
        'afd7d7',
        'afd7ff',
        'afff00',
        'afff5f',
        'afff87',
        'afffaf',
        'afffd7',
        'afffff',
        'd70000',
        'd7005f',
        'd70087',
        'd700af',
        'd700d7',
        'd700ff',
        'd75f00',
        'd75f5f',
        'd75f87',
        'd75faf',
        'd75fd7',
        'd75fff',
        'd78700',
        'd7875f',
        'd78787',
        'd787af',
        'd787d7',
        'd787ff',
        'd7af00',
        'd7af5f',
        'd7af87',
        'd7afaf',
        'd7afd7',
        'd7afff',
        'd7d700',
        'd7d75f',
        'd7d787',
        'd7d7af',
        'd7d7d7',
        'd7d7ff',
        'd7ff00',
        'd7ff5f',
        'd7ff87',
        'd7ffaf',
        'd7ffd7',
        'd7ffff',
        'ff0000',
        'ff005f',
        'ff0087',
        'ff00af',
        'ff00d7',
        'ff00ff',
        'ff5f00',
        'ff5f5f',
        'ff5f87',
        'ff5faf',
        'ff5fd7',
        'ff5fff',
        'ff8700',
        'ff875f',
        'ff8787',
        'ff87af',
        'ff87d7',
        'ff87ff',
        'ffaf00',
        'ffaf5f',
        'ffaf87',
        'ffafaf',
        'ffafd7',
        'ffafff',
        'ffd700',
        'ffd75f',
        'ffd787',
        'ffd7af',
        'ffd7d7',
        'ffd7ff',
        'ffff00',
        'ffff5f',
        'ffff87',
        'ffffaf',
        'ffffd7',
        'ffffff',
        '080808',
        '121212',
        '1c1c1c',
        '262626',
        '303030',
        '3a3a3a',
        '444444',
        '4e4e4e',
        '585858',
        '606060',
        '666666',
        '767676',
        '808080',
        '8a8a8a',
        '949494',
        '9e9e9e',
        'a8a8a8',
        'b2b2b2',
        'bcbcbc',
        'c6c6c6',
        'd0d0d0',
        'dadada',
        'e4e4e4',
        'eeeeee'
    ];
    private string $pos;
    private array $styleAr = [];
    private mixed $width = false;
    private int $align = STR_PAD_RIGHT;
    private int $padding = 0;

    public function __construct($string)
    {
        $this->string = $string;
    }

    public static function _($string)
    {
        return new ANSI($string);
    }

    public static function getCursorPosition(): array
    {
        $ttyprops = trim(`stty -g`);
        system('stty -icanon -echo');

        $term = fopen('/dev/tty', 'w');
        fwrite($term, "\033[6n");
        fclose($term);

        $buf = fread(STDIN, 16);

        system("stty '$ttyprops'");

        $matches = [];
        preg_match('/^\033\[(\d+);(\d+)R$/', $buf, $matches);

        $row = intval($matches[1]);
        $col = intval($matches[2]);
        return array($row, $col);
    }

    public static function __callStatic(string $name, array $arguments)
    {
        switch ($name) {
            case '':
                echo "\e[" . $arguments[0] . ';' . $arguments[1] . 'H';
                break;
        }
    }

    public static function goto($row, $col)
    {
        echo "\e[" . $row . ';' . $col . 'H';
    }

    public static function clear()
    {
        echo "\e[2J";
    }

    public static function getSprintf($string, $size): string
    {
        $added = 0;
        if (preg_match_all("/\\033\[[^m]*m/", $string, $ar)) {
            $added = strlen($string) - strlen(preg_replace('/\\033\[[^m]*m/', '', $string));
        }
        return sprintf('%-' . ($added + $size) . 's', $string);
    }

    public static function stripColors($string): array|string|null
    {
        return preg_replace('/\\033\[[^m]*m/', '', $string);
    }

    public function __toString(): string
    {
        $eParts = array_keys($this->styleAr);
        if (isset($this->fg)) {
            $eParts[] = '38;5;' . $this->fg;
        }
        if (isset($this->bg)) {
            $eParts[] = '48;5;' . $this->bg;
        }
        $str = $this->string;
        if ($this->padding !== 0) {
            $str = str_repeat(' ', $this->padding) . $str . str_repeat(' ', $this->padding);
        }
        if ($this->width !== false) {
            $str = str_pad($str, $this->width, " ", $this->align);
        }
        if (count($eParts)) {
            if (isset($this->fg) && !isset($this->bg)) {
                $reset = 39;
            } elseif (!isset($this->fg) && isset($this->bg)) {
                $reset = 49;
            } else {
                $reset = 0;
            }
            $rets = "";
            if (isset($this->pos)) {
                $rets = "\e[" . $this->pos . 'H';
            }
            return $rets . "\e[" . join(';', $eParts) . 'm' . $str . "\e[" . $reset . "m";
        }
        return $str;
    }

    public function __call(string $name, array $arguments)
    {
        $name = preg_replace('/purple/', 'magenta', $name);

        switch ($name) {
            case 'fg':
            case 'bg':
                $this->setColor($arguments[0], $name);
                break;
            case 'pos':
                $this->pos = $arguments[0] . ';' . $arguments[1];
                break;
            case 'reset':
            case 'bold':
            case 'dark':
            case 'italic':
            case 'underline':
            case 'blink':
            case 'reverse':
            case 'concealed':
            case 'strike':

            case 'black':
            case 'red':
            case 'green':
            case 'yellow':
            case 'blue':
            case 'magenta':
            case 'cyan':
            case 'light_gray':
            case 'default':
                $this->styleAr[$this->styles[$name]] = true;
                break;
            case 'setText':
                $this->string = $arguments[0];
                break;
            case 'width':
                $this->width = $arguments[0];
                break;
            case 'align_center':
                $this->align = STR_PAD_BOTH;
                break;
            case 'align_left':
                $this->align = STR_PAD_RIGHT;
                break;
            case 'align_right':
                $this->align = STR_PAD_LEFT;
                break;
            case 'padding':
                $this->padding = $arguments[0];
                break;
            default:
                if (isset($this->styles[$name])) {
                    $this->styleAr[$this->styles[$name]] = true;
                }
                break;
        }
        return $this;
    }

    private function setColor(int|string $color, $target): void
    {
        if (is_integer($color) && $color >= 0 && $color <= 254) {
            $this->$target = $color;
        } elseif (strlen($color) > 0 && $color[0] == '#') {
            $this->$target = $this->hexTo256($color);
        }
    }

    private function hexTo256($m)
    {
        $rgb = hexdec(substr($m, 1));
        $r = ($rgb >> 16) & 255;
        $g = ($rgb >> 8) & 255;
        $b = $rgb & 255;
        $diff = null;
        foreach ($this->webTo256 as $colorId => $_rgb) {
            $_rgb = hexdec($_rgb);
            $_r = ($_rgb >> 16) & 255;
            $_g = ($_rgb >> 8) & 255;
            $_b = $_rgb & 255;

            $d = sqrt(
                ($_r - $r) ** 2
                + ($_g - $g) ** 2
                + ($_b - $b) ** 2
            );

            if (null === $diff || $d <= $diff) {
                $diff = $d;
                $return = $colorId;
            }
        }
        return $return;
    }

    public function __invoke($string): static
    {
        $this->string = $string;
        return $this;
    }
}