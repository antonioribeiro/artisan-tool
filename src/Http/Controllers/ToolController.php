<?php

namespace PragmaRX\ArtisanTool\Http\Controllers;

use PragmaRX\ArtisanTool\File;
use Illuminate\Routing\Controller;
use PragmaRX\ArtisanTool\Support\Artisan;

class ToolController extends Controller
{
    public function index()
    {
        $this->clearTty();

        return app(Artisan::class)->commands();
    }

    public function clearTty()
    {
        app(Artisan::class)->clearTty();
    }

    public function readConsoleBuffer()
    {
        return app(Artisan::class)->readConsoleBuffer();
    }

    public function run($command)
    {
        return app(Artisan::class)->run($command);
    }
}
