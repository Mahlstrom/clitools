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

    private int $bg;
    private int $fg;
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
        $rets = "";
        if (isset($this->pos)) {
            $rets = "\e[" . $this->pos . 'H';
        }
        if (count($eParts)) {
//            if (isset($this->fg) && !isset($this->bg)) {
//                $reset = 39;
//            } elseif (!isset($this->fg) && isset($this->bg)) {
//                $reset = 49;
//            } else {
//                $reset = 0;
//            }
            $reset = 0;
            return $rets . "\e[" . join(';', $eParts) . 'm' . $str . "\e[" . $reset . "m";
        }
        return $rets . $str;
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
                if (isset(ANSI_STYLES[$name])) {
                    $this->styleAr[ANSI_STYLES[$name]] = true;
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

    private function hexTo256($m): int
    {
        $rgb = hexdec(substr($m, 1));
        $r = ($rgb >> 16) & 255;
        $g = ($rgb >> 8) & 255;
        $b = $rgb & 255;
        $diff = null;
        foreach (WEB_TO_256 as $colorId => $_rgb) {
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

    public static function resetScreen(): void
    {
        echo "\e[H";
        echo "\e[J";
        echo "\e[0m";
    }
}