<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\ContactRequest;
use App\Models\LearningArticle;
use App\Models\Product;
use App\Models\Prototype;
use App\Models\SiteContent;
use App\Models\SupportingDocument;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        $heroContent = SiteContent::query()->where('section_key', 'home.hero')->first();
        $products = Product::published()->get();
        $prototypes = Prototype::published()
            ->with('images')
            ->orderByDesc('is_featured')
            ->orderByDesc('testing_date')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
        $documents = SupportingDocument::publiclyVisible()
            ->orderByDesc('issue_date')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('public.home', [
            'heroContent' => $heroContent,
            'products' => $products,
            'prototypes' => $prototypes,
            'documents' => $documents,
        ]);
    }

    public function solutions(): View
    {
        $products = Product::published()->get();

        return view('public.solutions.index', [
            'products' => $products,
        ]);
    }

    public function soilsense(): View
    {
        return $this->renderSolutionDetail('soilsense');
    }

    public function watersense(): View
    {
        return $this->renderSolutionDetail('watersense');
    }

    public function envirosense(): View
    {
        return $this->renderSolutionDetail('envirosense');
    }

    public function terra(): View
    {
        return $this->renderSolutionDetail('terra');
    }

    public function showSolution(string $slug): View
    {
        return $this->renderSolutionDetail($slug);
    }

    public function platform(): View
    {
        $softwareProducts = Product::published()
            ->whereIn('slug', ['farm-management', 'business', 'intelligence'])
            ->get()
            ->keyBy('slug');

        $learningTopics = LearningArticle::published()->limit(12)->get();

        return view('public.platform', [
            'softwareProducts' => $softwareProducts,
            'learningTopics' => $learningTopics,
        ]);
    }

    public function enterprise(): View
    {
        $enterpriseProduct = Product::published()->where('slug', 'enterprise')->first();

        return view('public.enterprise', [
            'enterpriseProduct' => $enterpriseProduct,
        ]);
    }

    public function about(): View
    {
        $aboutContent = SiteContent::query()->where('section_key', 'about.overview')->first();
        $productsCount = Product::published()->count();

        return view('public.about', [
            'aboutContent' => $aboutContent,
            'productsCount' => $productsCount,
        ]);
    }

    public function contact(Request $request): View
    {
        $contactContent = SiteContent::query()->where('section_key', 'contact.office')->first();
        $products = Product::published()->get();

        return view('public.contact', [
            'contactContent' => $contactContent,
            'products' => $products,
            'preselectedType' => $request->string('type', 'enterprise_demo')->toString(),
            'preselectedModule' => $request->string('module')->toString(),
        ]);
    }

    public function submitContact(StoreContactRequest $request, AuditLogger $auditLogger): RedirectResponse
    {
        $contact = ContactRequest::create(array_merge(
            $request->safe()->only([
                'request_type',
                'full_name',
                'email',
                'phone',
                'organization_name',
                'role_title',
                'interested_modules',
                'land_scale',
                'message',
            ]),
            ['status' => 'new']
        ));

        $auditLogger->record('public.contact_request_submitted', $contact, [
            'request_type' => $contact->request_type,
            'email' => $contact->email,
        ]);

        return redirect()
            ->route('contact')
            ->with('status', 'Terima kasih. Permintaan konsultasi / demo REGEN Anda telah tercatat dan akan ditindaklanjuti oleh tim kami.');
    }

    private function renderSolutionDetail(string $slug): View
    {
        $product = Product::published()->where('slug', $slug)->firstOrFail();

        $relatedPrototypes = Prototype::published()
            ->when(
                in_array($slug, ['soilsense', 'watersense', 'envirosense', 'terra'], true),
                fn ($q) => $q->where('category', $slug)
            )
            ->orderByDesc('testing_date')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $siblingProducts = Product::published()
            ->where('slug', '!=', $slug)
            ->whereIn('slug', ['soilsense', 'watersense', 'envirosense', 'terra'])
            ->get();

        return view('public.solutions.show', [
            'product' => $product,
            'relatedPrototypes' => $relatedPrototypes,
            'siblingProducts' => $siblingProducts,
        ]);
    }
}
