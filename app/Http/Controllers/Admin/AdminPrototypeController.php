<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PrototypeCategory;
use App\Http\Controllers\Controller;
use App\Models\Prototype;
use App\Models\PrototypeImage;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPrototypeController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('category')->toString();
        $status = $request->string('status')->toString();
        $search = $request->string('q')->trim()->toString();

        $prototypes = Prototype::query()
            ->with('images')
            ->when($category !== '', fn ($q) => $q->where('category', $category))
            ->when($status !== '', fn ($q) => $q->where('publication_status', $status))
            ->when($search !== '', fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->orderByDesc('testing_date')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.prototypes', [
            'prototypes' => $prototypes,
            'categories' => PrototypeCategory::cases(),
            'selectedCategory' => $category,
            'selectedStatus' => $status,
            'search' => $search,
        ]);
    }

    public function store(Request $request, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:220'],
            'category' => ['required', Rule::enum(PrototypeCategory::class)],
            'description' => ['required', 'string', 'max:8000'],
            'technical_notes' => ['nullable', 'string', 'max:5000'],
            'testing_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:200'],
            'publication_status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
            'is_featured' => ['nullable', 'boolean'],
            'video_url' => [
                'nullable',
                'url:https',
                'max:500',
                'regex:/^https:\/\/(www\.)?(youtube\.com|youtu\.be|player\.vimeo\.com|vimeo\.com)\/.+/i',
            ],
            'thumbnail' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'array', 'max:8'],
            'gallery_images.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm', 'max:20480'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $slug = Str::slug($validated['title']).'-'.strtolower(Str::random(5));

        $thumbnailPath = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('prototypes/thumbnails', 'public')
            : 'images/regen-soilsense-probe.jpg';

        $videoPath = $request->hasFile('video_file')
            ? $request->file('video_file')->store('prototypes/videos', 'public')
            : null;

        $pdfPath = $request->hasFile('pdf_file')
            ? $request->file('pdf_file')->store('prototypes/docs', 'public')
            : null;

        $prototype = Prototype::create([
            'slug' => $slug,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'technical_notes' => $validated['technical_notes'] ?? null,
            'testing_date' => $validated['testing_date'] ?? null,
            'location' => $validated['location'] ?? null,
            'thumbnail_path' => $thumbnailPath,
            'video_path' => $videoPath,
            'video_url' => $validated['video_url'] ?? null,
            'pdf_path' => $pdfPath,
            'is_featured' => $request->boolean('is_featured'),
            'publication_status' => $validated['publication_status'],
            'created_by' => $request->user()?->id,
        ]);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $idx => $imgFile) {
                $storedImg = $imgFile->store('prototypes/gallery', 'public');
                PrototypeImage::create([
                    'prototype_id' => $prototype->id,
                    'image_path' => $storedImg,
                    'caption' => $prototype->title.' #'.($idx + 1),
                    'sort_order' => $idx + 1,
                ]);
            }
        }

        $auditLogger->record('admin.prototype_created', $prototype, [
            'publication_status' => $prototype->publication_status,
        ]);

        return back()->with('status', "Dokumentasi prototipe '{$prototype->title}' berhasil disimpan.");
    }

    public function update(Request $request, Prototype $prototype, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:220'],
            'category' => ['required', Rule::enum(PrototypeCategory::class)],
            'publication_status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
            'location' => ['nullable', 'string', 'max:200'],
            'testing_date' => ['nullable', 'date'],
            'description' => ['required', 'string', 'max:8000'],
            'technical_notes' => ['nullable', 'string', 'max:5000'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $prototype->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'publication_status' => $validated['publication_status'],
            'location' => $validated['location'] ?? null,
            'testing_date' => $validated['testing_date'] ?? null,
            'description' => $validated['description'],
            'technical_notes' => $validated['technical_notes'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        $auditLogger->record('admin.prototype_updated', $prototype, [
            'publication_status' => $prototype->publication_status,
        ]);

        return back()->with('status', "Prototipe '{$prototype->title}' berhasil diperbarui.");
    }

    public function destroy(Prototype $prototype, AuditLogger $auditLogger): RedirectResponse
    {
        $title = $prototype->title;
        $auditLogger->record('admin.prototype_deleted', $prototype, ['title' => $title]);
        $prototype->delete();

        return back()->with('status', "Prototipe '{$title}' telah dihapus.");
    }
}
