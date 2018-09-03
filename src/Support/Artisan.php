<?php

namespace PragmaRX\ArtisanTool\Support;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Illuminate\Support\Facades\Artisan as IlluminateArtisan;

class Artisan
{
    const TTY_SESSION_KEY = 'artisan-tool-tty-key';

    /**
     * Clear the TTY.
     *
     * @param null $string
     */
    public function clearTty($string = null)
    {
        $this->clearCurrentCommandLine();

        if (file_exists($tty = $this->getConsoleTtyFile())) {
            unlink($this->getConsoleTtyFile());
        }

        if ($string) {
            file_put_contents($tty, $string . "<br>");
        }
    }

    /**
     * Compile all options.
     *
     * @param $command
     * @return mixed
     */
    protected function compileOptions($command)
    {
        return collect($command->getDefinition()->getOptions())->map(function (
            $option
        ) {
            return [
                'name' => $name = $option->getName(),
                'option' => "--{$name}",
                'default' => $option->getDefault(),
                'shortcut' => $option->getShortcut(),
                'description' => $option->getDescription(),
                'needsInput' => !is_bool($option->getDefault()),
                'isRequired' => $option->isValueRequired(),
                'selected' => false,
                'value' => $option->getDefault(),
                'isArray' => $option->isArray(),
            ];
        });
    }

    /**
     * Compile all arguments.
     *
     * @param $command
     * @return mixed
     */
    protected function compileArguments($command)
    {
        return collect($command->getDefinition()->getArguments())->map(
            function ($argument) {
                return [
                    'name' => $argument->getName(),
                    'default' => $argument->getDefault(),
                    'description' => $argument->getDescription(),
                    'value' => $argument->getDefault(),
                    'isRequired' => $argument->isRequired(),
                    'isArray' => $argument->isArray(),
                ];
            }
        );
    }

    /**
     * Get command info array.
     *
     * @param $command
     * @return array
     */
    protected function getCommand($command)
    {
        return [
            'name' => $command->getName(),
            'description' => $command->getDescription(),
            'options' => $this->compileOptions($command),
            'arguments' => $this->compileArguments($command),
        ];
    }

    /**
     * Get all commands.
     *
     * @return \Illuminate\Support\Collection
     */
    public function commands()
    {
        return collect(IlluminateArtisan::all())->mapWithKeys(function (
            $command
        ) {
            return [$command->getName() => $this->getCommand($command)];
        });
    }

    /**
     * Get the console tty file path.
     *
     * @return string
     */
    protected function getConsoleTtyFile(): string
    {
        return storage_path('app/console.tty');
    }

    /**
     * Make the command string.
     *
     * @param $command
     * @return string
     */
    public function makeCommand($command)
    {
        return sprintf("php %s {$command}", base_path('artisan'));
    }

    /**
     * Execute artisan command.
     *
     * @param $command
     */
    public function run($command)
    {
        $this->storeCurrentCommandLine(
            config('app.name') .
                ':~$ ' .
                ($command = $this->makeCommand($this->sanitize($command)))
        );

        app(Executor::class)->exec(
            $command,
            base_path(),
            $this->getConsoleTtyFile()
        );
    }

    /**
     * Read the console buffer.
     *
     * @return mixed
     */
    public function readConsoleBuffer()
    {
        return (
            $this->getCurrentCommandLine() .
            $this->toHtml(
                file_exists($tty = $this->getConsoleTtyFile())
                    ? file_get_contents($tty)
                    : ''
            )
        );
    }

    /**
     * Sanitize command string.
     *
     * @param $command
     * @return null|string|string[]
     */
    protected function sanitize($command)
    {
        return preg_replace('/\s+/', ' ', $command);
    }

    /**
     * Get the current command line.
     *
     * @return string
     */
    private function getCurrentCommandLine()
    {
        return session()->get(static::TTY_SESSION_KEY) . '<br>';
    }

    /**
     * Store the current command line in a session var.
     *
     * @param string $string
     */
    private function storeCurrentCommandLine(string $string)
    {
        session()->put(static::TTY_SESSION_KEY, $string);
    }

    /**
     * Clear the current command line.
     *
     * @param string $string
     */
    private function clearCurrentCommandLine()
    {
        session()->forget(static::TTY_SESSION_KEY);
    }

    /**
     * Convert ansi output to HTML
     * @param string $string
     * @return mixed
     */
    public function toHtml(string $string)
    {
        return app(AnsiToHtmlConverter::class)->convert($string);
    }
}
