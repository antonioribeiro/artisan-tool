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

    private function addScript($command, $tempFile)
    {
        return "/usr/bin/script -q {$tempFile} {$command}";
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
        $tty,
        Closure $callback = null,
        $timeout = null
    ) {
        $process = new Process($this->addScript($command, $tty), $runDir);

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

    /**
     * Execute a shell command.
     *
     * @param $command
     *
     * @return mixed
     */
    public function shellExec($command)
    {
        return shell_exec($command);
    }
}
