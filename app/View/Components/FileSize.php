<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;
use Illuminate\View\View;

class FileSize extends Component
{
    public string $display = '';

    public function __construct(public string $path, public string $disk = 'public')
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            $bytes = Storage::disk($disk)->size($path);
            $this->display = $this->format($bytes);
        }
    }

    private function format(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }
        return round($bytes / 1024, 1) . ' KB';
    }

    public function render(): View
    {
        return view('components.file-size');
    }
}
