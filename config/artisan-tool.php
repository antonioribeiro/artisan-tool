<?php

return [
    /**
     * Specify which commands should be whitelisted to be shown in the nova tool
     */

    'whitelistedCommands' => [],

    /**
     * Piper
     *
     * This is the command used to execute an Artisan command and
     * get (pipe) the output to a file, to be displayed in the web console.
     *
     * It uses the script Linux command: http://man7.org/linux/man-pages/man1/script.1.html
     *
     * Examples:
     *   'script -q %ttyFile% %command%',     // current macOS script
     *   'script -q %ttyFile% -c "%command%"',  // old macOS script
     *
     */

    'piper' => 'script -q %ttyFile% %command%',

    /**
     * Console tty filename, used to pipe command results.
     * Command prompt to show in the command log window.
     */

    'console' => [
        'tty-file' => storage_path('app/console.tty'),

        'prompt' => config('app.name') . ':~$ '
    ],

    /**
     * PHP executable file/command
     */

    'php' => [
        'executable' => 'php'
    ]
];
