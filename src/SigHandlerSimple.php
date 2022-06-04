<?php
/**
 * Created by PhpStorm.
 * User: mahlstrom
 * Date: 2017-01-28
 * Time: 01:05
 */
namespace mahlstrom;

/**
 * Class SigHandlerSimple
 */
class SigHandlerSimple
{
    private $pidFile;
    private $resetScreen = true;

    /**
     * SigHandlerSimple constructor.
     * @param string $e
     */
    public function __construct(string $e = '')
    {
        if ($e) {
            $this->resetScreen = false;
        }
        $bt = debug_backtrace();
        pcntl_signal(SIGTERM, [$this, 'signalHandler']);// Termination ('kill' was called)
        pcntl_signal(SIGHUP, [$this, 'signalHandler']); // Terminal log-out
        pcntl_signal(SIGINT, [$this, 'signalHandler']); // Interrupted (Ctrl-C is pressed)
        $pidFileName = '/usr/local/var/run/' . $e . basename($bt[0]['file']) . '.pid';
        $pidFile = @fopen($pidFileName, 'c');
        if (!$pidFile) {
            die("Could not open $pidFileName\n");
        }
        if (!@flock($pidFile, LOCK_EX | LOCK_NB)) {
            die("Already running?\n");
        }
        ftruncate($pidFile, 0);
        fwrite($pidFile, getmypid());
        $this->pidFile = $pidFile;
        echo "\033[?25l";
    }

    /**
     * @param mixed $signal
     */
    public function signalHandler($signal)
    {
        $pidFile = $this->pidFile;
        ftruncate($pidFile, 0);
        if ($this->resetScreen) {
            echo "\033[?25h";
            echo "\033[H";
            echo "\033[2J";
        }
        exit;
    }
}