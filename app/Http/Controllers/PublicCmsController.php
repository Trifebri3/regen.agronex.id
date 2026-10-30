<?php

namespace App\Http\Controllers;

use App\Enums\DocumentCategory;
use App\Enums\DocumentStatus;
use App\Enums\PrototypeCategory;
use App\Models\Prototype;
use App\Models\SupportingDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicCmsController extends Controller
{
    public function prototypes(Request $request): View
    {
        $selectedCategory = $request->string('category')->toString();
        $search = $request->string('q')->trim()->toString();

        $query = Prototype::published()
            ->with('images')
            ->when($selectedCategory !== '', fn ($q) => $q->where('category', $selectedCategory))
            ->when($search !== '', function ($q) use ($search): void {
                $q->where(function ($sub) use ($search): void {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('testing_date')
            ->orderByDesc('id');

        $prototypes = $query->paginate(9)->withQueryString();
        $featuredPrototypes = Prototype::published()
            ->with('images')
            ->where('is_featured', true)
            ->orderByDesc('testing_date')
            ->orderByDesc('id')
            ->limit(2)
            ->get();

        return view('public.prototype.index', [
            'prototypes' => $prototypes,
            'featuredPrototypes' => $featuredPrototypes,
            'categories' => PrototypeCategory::cases(),
            'selectedCategory' => $selectedCategory,
            'search' => $search,
        ]);
    }

    public function showPrototype(Request $request, Prototype $prototype): View
    {
        $canPreviewDraft = $request->user()?->role->canAccessAdminSection('prototypes') ?? false;

        if ($prototype->publication_status !== 'published' && ! $canPreviewDraft) {
            abort(404);
        }

        $prototype->load('images');

        $chronologicalUpdates = Prototype::published()
            ->where('id', '!=', $prototype->id)
            ->orderByDesc('testing_date')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('public.prototype.show', [
            'prototype' => $prototype,
            'chronologicalUpdates' => $chronologicalUpdates,
        ]);
    }

    public function documents(Request $request): View
    {
        $selectedCategory = $request->string('category')->toString();
        $selectedStatus = $request->string('status')->toString();
        $search = $request->string('q')->trim()->toString();

        $documents = SupportingDocument::publiclyVisible()
            ->when($selectedCategory !== '', fn ($q) => $q->where('category', $selectedCategory))
            ->when($selectedStatus !== '', fn ($q) => $q->where('registration_status', $selectedStatus))
            ->when($search !== '', function ($q) use ($search): void {
                $q->where(function ($sub) use ($search): void {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$search}%")
                        ->orWhere('issuing_institution', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('issue_date')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('public.pendukung.index', [
            'documents' => $documents,
            'categories' => DocumentCategory::cases(),
            'statuses' => array_filter(
                DocumentStatus::cases(),
                fn (DocumentStatus $status) => $status !== DocumentStatus::Draft
            ),
            'selectedCategory' => $selectedCategory,
            'selectedStatus' => $selectedStatus,
            'search' => $search,
        ]);
    }

    public function downloadDocument(Request $request, SupportingDocument $supportingDocument): StreamedResponse
    {
        $isAdminAuthorized = $request->user()?->role->canAccessAdminSection('documents') ?? false;

        if (! $isAdminAuthorized && ! $supportingDocument->isPubliclyDownloadable()) {
            abort(403, 'Dokumen ini tidak tersedia untuk unduhan publik.');
        }

        if (empty($supportingDocument->file_path) || ! Storage::disk($supportingDocument->disk)->exists($supportingDocument->file_path)) {
            abort(404, 'Berkas dokumen tidak ditemukan di penyimpanan aman.');
        }

        $downloadName = $supportingDocument->original_filename ?: basename($supportingDocument->file_path);

        return Storage::disk($supportingDocument->disk)->download(
            $supportingDocument->file_path,
            $downloadName
        );
    }
}
