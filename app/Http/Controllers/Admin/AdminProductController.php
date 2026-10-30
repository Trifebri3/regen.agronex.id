<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products', [
            'products' => Product::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:30', Rule::unique('products', 'code')],
            'tagline' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(['hardware_iot', 'spatial', 'intelligence', 'software', 'enterprise'])],
            'readiness_status' => ['required', 'string', Rule::in(['operational', 'pilot_demo', 'roadmap'])],
            'short_description' => ['required', 'string', 'max:600'],
            'description' => ['required', 'string', 'max:5000'],
            'features_text' => ['required', 'string', 'max:3000'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $slug = Str::slug($validated['name']);
        if (Product::query()->where('slug', $slug)->exists()) {
            $slug .= '-'.strtolower(Str::random(4));
        }

        $features = array_values(array_filter(array_map(
            'trim',
            preg_split('/\r\n|\r|\n/', $validated['features_text']) ?: []
        )));

        $product = Product::create([
            'slug' => $slug,
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'tagline' => $validated['tagline'],
            'category' => $validated['category'],
            'deployment_modes' => ['modular', 'integrated'],
            'readiness_status' => $validated['readiness_status'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'features' => $features,
            'specifications' => ['Deployment' => 'Modular & Integrated'],
            'hero_image' => 'images/regen-soilsense-probe.jpg',
            'is_published' => $request->boolean('is_published', true),
            'sort_order' => (int) ($validated['sort_order'] ?? 10),
        ]);

        $auditLogger->record('admin.product_created', $product);

        return back()->with('status', "Produk {$product->name} berhasil ditambahkan ke katalog.");
    }

    public function update(Request $request, Product $product, AuditLogger $auditLogger): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'tagline' => ['required', 'string', 'max:255'],
            'readiness_status' => ['required', 'string', Rule::in(['operational', 'pilot_demo', 'roadmap'])],
            'short_description' => ['required', 'string', 'max:600'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $product->update([
            'name' => $validated['name'],
            'tagline' => $validated['tagline'],
            'readiness_status' => $validated['readiness_status'],
            'short_description' => $validated['short_description'],
            'is_published' => $request->boolean('is_published'),
        ]);

        $auditLogger->record('admin.product_updated', $product);

        return back()->with('status', "Produk {$product->name} berhasil diperbarui.");
    }

    public function destroy(Product $product, AuditLogger $auditLogger): RedirectResponse
    {
        $name = $product->name;
        $auditLogger->record('admin.product_deleted', $product, ['name' => $name]);
        $product->delete();

        return back()->with('status', "Produk {$name} telah dihapus.");
    }
}
