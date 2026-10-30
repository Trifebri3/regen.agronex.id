<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('tagline');
            $table->string('category');
            $table->json('deployment_modes');
            $table->string('readiness_status')->default('operational');
            $table->text('short_description');
            $table->longText('description');
            $table->json('features');
            $table->json('specifications')->nullable();
            $table->json('metrics')->nullable();
            $table->string('hero_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();
            $table->string('page')->index();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('body')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('prototypes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category')->index();
            $table->longText('description');
            $table->longText('technical_notes')->nullable();
            $table->date('testing_date')->nullable();
            $table->string('location')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('video_path')->nullable();
            $table->string('video_url')->nullable();
            $table->string('pdf_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('publication_status')->default('draft')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('prototype_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prototype_id')->constrained('prototypes')->cascadeOnDelete();
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('supporting_documents', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category')->index();
            $table->string('document_type');
            $table->text('description')->nullable();
            $table->string('document_number')->nullable();
            $table->string('issuing_institution')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('registration_status')->default('draft')->index();
            $table->string('disk')->default('local');
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('visibility')->default('private')->index();
            $table->boolean('allow_download')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('media_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('disk')->default('public');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('collection')->default('general')->index();
            $table->string('alt_text')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_type')->default('product_inquiry');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('role_title')->nullable();
            $table->json('interested_modules')->nullable();
            $table->string('land_scale')->nullable();
            $table->text('message');
            $table->string('status')->default('new')->index();
            $table->text('admin_notes')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_requests');
        Schema::dropIfExists('media_items');
        Schema::dropIfExists('supporting_documents');
        Schema::dropIfExists('prototype_images');
        Schema::dropIfExists('prototypes');
        Schema::dropIfExists('site_contents');
        Schema::dropIfExists('products');
    }
};
