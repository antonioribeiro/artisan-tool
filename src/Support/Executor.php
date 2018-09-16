<?php

namespace PragmaRX\ArtisanTool\Support;

use Closure;
use Carbon\Carbon;
use Symfony\Component\Process\Process;

class Executor
{
    public $time;

    public $startedAt;

    public $endedAt;

    /**
     * Add command output piper.
     *
     * @param $command
     * @param $ttyFile
     * @return string
     */
    private function addPiper($command, $ttyFile)
    {
        return 'script -q ' . $ttyFile . ' -c "' . $command . '"';
    }

    /**
     * Execute one command.
     *
     * @param $command
     * @param null         $runDir
     * @param Closure|null $callback
     * @param null         $timeout
     *
     * @return Process
     */
    public function exec(
        $command,
        $runDir = null,
        $ttyFile,
        Closure $callback = null,
        $timeout = null
    ) {
        $process = new Process($this->addPiper($command, $ttyFile), $runDir);

        $process->setTimeout($timeout);

        $this->startedAt = Carbon::now();

        $process->run($callback);

        $this->endedAt = Carbon::now();

        return $process;
    }

    /**
     * Get the elapsed time formatted for humans.
     *
     * @return mixed
     */
    public function elapsedForHumans()
    {
        return $this->endedAt->diffForHumans($this->startedAt);
    }
}
