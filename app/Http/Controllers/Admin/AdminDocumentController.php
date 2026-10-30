<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DocumentCategory;
use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Models\SupportingDocument;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDocumentController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('category')->toString();
        $visibility = $request->string('visibility')->toString();

        $documents = SupportingDocument::query()
            ->when($category !== '', fn ($q) => $q->where('category', $category))
            ->when($visibility !== '', fn ($q) => $q->where('visibility', $visibility))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.documents', [
            'documents' => $documents,
            'categories' => DocumentCategory::cases(),
            'statuses' => DocumentStatus::cases(),
            'selectedCategory' => $category,
            'selectedVisibility' => $visibility,
        ]);
    }

    public function store(Request $request, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:240'],
            'category' => ['required', Rule::enum(DocumentCategory::class)],
            'document_type' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:3000'],
            'document_number' => ['nullable', 'string', 'max:120'],
            'issuing_institution' => ['nullable', 'string', 'max:180'],
            'issue_date' => ['nullable', 'date'],
            'expiration_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'registration_status' => ['required', Rule::enum(DocumentStatus::class)],
            'visibility' => ['required', 'string', Rule::in(['public', 'private'])],
            'allow_download' => ['nullable', 'boolean'],
            'document_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $filePath = null;
        $originalFilename = null;
        $mimeType = null;
        $fileSize = null;

        if ($request->hasFile('document_file')) {
            $uploaded = $request->file('document_file');
            // Always store on private 'local' disk so private documents are never exposed via public URLs
            $filePath = $uploaded->store('documents', 'local');
            $originalFilename = Str::ascii($uploaded->getClientOriginalName());
            $mimeType = $uploaded->getMimeType();
            $fileSize = $uploaded->getSize();
        }

        $doc = SupportingDocument::create([
            'slug' => Str::slug($validated['title']).'-'.strtolower(Str::random(5)),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'document_type' => $validated['document_type'],
            'description' => $validated['description'] ?? null,
            'document_number' => $validated['document_number'] ?? null,
            'issuing_institution' => $validated['issuing_institution'] ?? null,
            'issue_date' => $validated['issue_date'] ?? null,
            'expiration_date' => $validated['expiration_date'] ?? null,
            'registration_status' => $validated['registration_status'],
            'disk' => 'local',
            'file_path' => $filePath,
            'original_filename' => $originalFilename,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'visibility' => $validated['visibility'],
            'allow_download' => $request->boolean('allow_download'),
            'created_by' => $request->user()?->id,
        ]);

        $auditLogger->record('admin.supporting_document_created', $doc, [
            'visibility' => $doc->visibility,
            'category' => $doc->category->value,
        ]);

        return back()->with('status', "Dokumen '{$doc->title}' berhasil disimpan di repositori aman.");
    }

    public function update(Request $request, SupportingDocument $supportingDocument, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:240'],
            'registration_status' => ['required', Rule::enum(DocumentStatus::class)],
            'visibility' => ['required', 'string', Rule::in(['public', 'private'])],
            'allow_download' => ['nullable', 'boolean'],
            'document_number' => ['nullable', 'string', 'max:120'],
        ]);

        $supportingDocument->update([
            'title' => $validated['title'],
            'registration_status' => $validated['registration_status'],
            'visibility' => $validated['visibility'],
            'allow_download' => $request->boolean('allow_download'),
            'document_number' => $validated['document_number'] ?? $supportingDocument->document_number,
        ]);

        $auditLogger->record('admin.supporting_document_updated', $supportingDocument, [
            'visibility' => $supportingDocument->visibility,
        ]);

        return back()->with('status', "Dokumen '{$supportingDocument->title}' berhasil diperbarui.");
    }

    public function destroy(SupportingDocument $supportingDocument, AuditLogger $auditLogger): RedirectResponse
    {
        if ($supportingDocument->file_path && Storage::disk($supportingDocument->disk)->exists($supportingDocument->file_path)) {
            Storage::disk($supportingDocument->disk)->delete($supportingDocument->file_path);
        }

        $title = $supportingDocument->title;
        $auditLogger->record('admin.supporting_document_deleted', $supportingDocument, ['title' => $title]);
        $supportingDocument->delete();

        return back()->with('status', "Dokumen '{$title}' telah dihapus.");
    }

    public function download(SupportingDocument $supportingDocument): StreamedResponse
    {
        if (! $supportingDocument->file_path || ! Storage::disk($supportingDocument->disk)->exists($supportingDocument->file_path)) {
            abort(404, 'Berkas dokumen tidak tersedia.');
        }

        return Storage::disk($supportingDocument->disk)->download(
            $supportingDocument->file_path,
            $supportingDocument->original_filename ?: basename($supportingDocument->file_path)
        );
    }
}
