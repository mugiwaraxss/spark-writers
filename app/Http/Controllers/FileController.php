<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function download(File $file)
    {
        // Check permissions
        $this->authorize('download', $file);
        
        // Check if file exists in storage
        if (!Storage::exists($file->path)) {
            return back()->with('error', 'File not found.');
        }
        
        return Storage::download($file->path, $file->original_name);
    }
} 