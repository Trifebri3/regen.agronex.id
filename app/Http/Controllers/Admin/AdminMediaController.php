<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminMediaController extends Controller
{
    public function index(): View
    {
        return view('admin.media', [
            'mediaItems' => MediaItem::query()->with('uploader')->latest()->paginate(16),
        ]);
    }

    public function store(Request $request, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'collection' => ['required', 'string', Rule::in(['general', 'prototype', 'land', 'document'])],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'media_file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf,mp4', 'max:10240'],
        ]);

        $file = $request->file('media_file');
        $disk = $validated['collection'] === 'document' ? 'local' : 'public';
        $storedPath = $file->store('media/'.$validated['collection'], $disk);

        $item = MediaItem::create([
            'uploaded_by' => $request->user()?->id,
            'title' => $validated['title'],
            'disk' => $disk,
            'file_path' => $storedPath,
            'original_filename' => Str::ascii($file->getClientOriginalName()),
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'file_size' => (int) $file->getSize(),
            'collection' => $validated['collection'],
            'alt_text' => $validated['alt_text'] ?? null,
        ]);

        $auditLogger->record('admin.media_uploaded', $item, [
            'disk' => $disk,
            'mime_type' => $item->mime_type,
        ]);

        return back()->with('status', "Media '{$item->title}' berhasil diunggah.");
    }

    public function destroy(MediaItem $mediaItem, AuditLogger $auditLogger): RedirectResponse
    {
        if (Storage::disk($mediaItem->disk)->exists($mediaItem->file_path)) {
            Storage::disk($mediaItem->disk)->delete($mediaItem->file_path);
        }

        $title = $mediaItem->title;
        $auditLogger->record('admin.media_deleted', $mediaItem, ['title' => $title]);
        $mediaItem->delete();

        return back()->with('status', "Media '{$title}' telah dihapus.");
    }
}
