<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ContactRequest;
use App\Models\Device;
use App\Models\Farm;
use App\Models\Organization;
use App\Models\Product;
use App\Models\Prototype;
use App\Models\SiteContent;
use App\Models\SupportingDocument;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDashboardController extends Controller
{
    public function overview(): View
    {
        return view('admin.overview', [
            'stats' => [
                'products' => Product::count(),
                'prototypes_published' => Prototype::published()->count(),
                'prototypes_total' => Prototype::count(),
                'documents_public' => SupportingDocument::publiclyVisible()->count(),
                'documents_total' => SupportingDocument::count(),
                'organizations' => Organization::count(),
                'farms' => Farm::count(),
                'devices' => Device::count(),
                'users' => User::count(),
                'new_contacts' => ContactRequest::query()->where('status', 'new')->count(),
            ],
            'recentContacts' => ContactRequest::query()->latest()->limit(5)->get(),
            'recentLogs' => AuditLog::query()->with(['user', 'organization'])->latest()->limit(8)->get(),
        ]);
    }

    public function websiteContent(): View
    {
        return view('admin.website-content', [
            'contents' => SiteContent::query()->with('editor')->orderBy('page')->orderBy('section_key')->get(),
        ]);
    }

    public function updateWebsiteContent(Request $request, SiteContent $siteContent, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:350'],
            'body' => ['nullable', 'string', 'max:5000'],
        ]);

        $siteContent->update(array_merge($validated, [
            'updated_by' => $request->user()?->id,
        ]));

        $auditLogger->record('admin.site_content_updated', $siteContent, [
            'section_key' => $siteContent->section_key,
        ]);

        return back()->with('status', "Konten bagian {$siteContent->section_key} berhasil diperbarui.");
    }

    public function farms(): View
    {
        $farms = Farm::query()
            ->with(['organization', 'manager'])
            ->withCount(['lands', 'cropCycles', 'devices'])
            ->orderBy('organization_id')
            ->orderBy('name')
            ->get();

        return view('admin.farms', [
            'farms' => $farms,
        ]);
    }

    public function devices(): View
    {
        $devices = Device::query()
            ->with(['organization', 'farm', 'latestReading'])
            ->orderByDesc('last_seen_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.devices', [
            'devices' => $devices,
        ]);
    }

    public function contacts(): View
    {
        $contacts = ContactRequest::query()
            ->with('handler')
            ->orderByRaw("CASE WHEN status = 'new' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.contacts', [
            'contacts' => $contacts,
        ]);
    }

    public function updateContact(Request $request, ContactRequest $contactRequest, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['new', 'in_review', 'contacted', 'closed'])],
            'admin_notes' => ['nullable', 'string', 'max:1500'],
        ]);

        $contactRequest->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $contactRequest->admin_notes,
            'handled_by' => $request->user()?->id,
        ]);

        $auditLogger->record('admin.contact_request_updated', $contactRequest, [
            'status' => $contactRequest->status,
        ]);

        return back()->with('status', 'Status permintaan kontak / demo berhasil diperbarui.');
    }

    public function reports(): View
    {
        $organizations = Organization::query()
            ->withCount(['farms', 'lands', 'cropCycles', 'devices', 'users'])
            ->orderBy('name')
            ->get();

        return view('admin.reports', [
            'organizations' => $organizations,
            'totalPrototypes' => Prototype::count(),
            'totalDocuments' => SupportingDocument::count(),
        ]);
    }

    public function exportBackupManifest(AuditLogger $auditLogger): StreamedResponse
    {
        $auditLogger->record('admin.system_backup_exported', null, [
            'exported_at' => now()->toIso8601String(),
        ]);

        $payload = [
            'platform' => 'REGEN by AGRONEX',
            'exported_at' => now()->toIso8601String(),
            'products' => Product::query()->get()->toArray(),
            'site_contents' => SiteContent::query()->get()->toArray(),
            'prototypes' => Prototype::query()->with('images')->get()->toArray(),
            'supporting_documents_metadata' => SupportingDocument::query()->get()->toArray(),
            'organizations_summary' => Organization::query()->withCount(['farms', 'lands', 'devices'])->get()->toArray(),
        ];

        $filename = 'regen-backup-manifest-'.now()->format('Ymd-His').'.json';

        return response()->streamDownload(function () use ($payload): void {
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function settings(): View
    {
        return view('admin.settings', [
            'auditLogs' => AuditLog::query()
                ->with(['user', 'organization'])
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->paginate(20),
        ]);
    }
}
