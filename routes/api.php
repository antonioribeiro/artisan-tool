<?php

use Illuminate\Support\Facades\Route;

Route::get(
    'artisan-tool',
    \PragmaRX\ArtisanTool\Http\Controllers\ToolController::class . '@index'
);

Route::get(
    'artisan-tool-run/{command}',
    \PragmaRX\ArtisanTool\Http\Controllers\ToolController::class . '@run'
);

Route::get(
    'artisan-tool-console-buffer',
    \PragmaRX\ArtisanTool\Http\Controllers\ToolController::class .
        '@readConsoleBuffer'
);

Route::get(
    'artisan-tool-clear-tty',
    \PragmaRX\ArtisanTool\Http\Controllers\ToolController::class . '@clearTty'
);
