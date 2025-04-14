<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function downloadAttachment(Message $message)
    {
        // Check permissions
        $this->authorize('view', $message);
        
        // Check if attachment exists
        if (!$message->attachment_path || !Storage::exists($message->attachment_path)) {
            return back()->with('error', 'Attachment not found.');
        }
        
        return Storage::download($message->attachment_path, $message->attachment_name);
    }
} 